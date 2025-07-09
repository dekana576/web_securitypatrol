<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataPatrol;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class DataPatrolAdminController extends Controller
{
    public function index()
    {
        return view('admin.data_patrol.data_patrol');
    }

    public function getData(Request $request)
    {
        $data = DataPatrol::with(['region', 'salesOffice', 'checkpoint', 'user'])->latest();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('region_name', fn($row) => $row->region->name ?? '-')
            ->addColumn('sales_office_name', fn($row) => $row->salesOffice->sales_office_name ?? '-')
            ->addColumn('checkpoint_name', fn($row) => $row->checkpoint->checkpoint_name ?? '-')
            ->addColumn('security_name', fn($row) => $row->user->name ?? '-') // <= disini
            ->addColumn('tanggal', fn($row) => $row->tanggal)
            ->addColumn('kriteria_result', function ($row) {
                $hasil = json_decode($row->kriteria_result, true);

                $isNegative = collect($hasil)->filter(function ($val) {
                    return stripos($val, 'tidak') !== false || stripos($val, 'negative') !== false;
                })->isNotEmpty();

                if ($isNegative) {
                    return '<span class="badge bg-danger text-white">Tidak Aman</span>';
                } else {
                    return '<span class="badge bg-success text-white">Aman</span>';
                }
            })
            ->addColumn('status', function ($row) {
                if ($row->status === 'submitted') {
                    return '<span class="badge bg-warning text-white">Submitted</span>';
                } elseif ($row->status === 'approved') {
                    return '<span class="badge bg-success text-white">Approved</span>';
                }
                return '<span class="badge bg-secondary text-white">' . ucfirst($row->status) . '</span>';
            })

           ->addColumn('action', function ($row) {
                $deleteUrl = route('data_patrol.destroy', $row->id);
                $viewUrl = route('data_patrol.show', $row->id);

                return '
                    <a href="' . $viewUrl . '" class="action-icon view-icon view" title="Lihat">
                        <i class="fa-solid fa-eye"></i>
                    </a>
                    <a href="#" class="action-icon approve-icon approve" data-id="' . $row->id . '" title="Setujui">
                        <i class="fa-solid fa-circle-check"></i>
                    </a>
                    <a href="#" class="action-icon delete-icon delete" data-url="' . $deleteUrl . '" title="Hapus">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                ';
            })
            ->rawColumns(['kriteria_result', 'action', 'status'])
            ->make(true);

            
    }

    public function show($id)
    {
        $dataPatrol = DataPatrol::with(['region', 'salesOffice', 'checkpoint', 'user'])->findOrFail($id);
        
        return view('admin.data_patrol.view_data_patrol', compact('dataPatrol'));
    }

    public function updateFeedback(Request $request, $id)
    {
        $request->validate([
            'feedback_admin' => 'nullable|string|max:1000',
        ]);

        $dataPatrol = DataPatrol::findOrFail($id);
        $dataPatrol->update([
            'feedback_admin' => $request->feedback_admin,
        ]);

        return redirect()->route('data_patrol.show', $id)->with('success', 'Feedback berhasil disimpan.');
    }

    public function approve($id)
    {
        $data = DataPatrol::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data patroli tidak ditemukan.'
            ], 404);
        }

        $data->status = 'approved';
        $data->save();

        return response()->json([
            'success' => true,
            'message' => 'Data patroli berhasil disetujui.'
        ]);
    }

    public function approveView($id)
    {
        $data = DataPatrol::find($id);

        if (!$data) {
            return redirect()->back()->with('error', 'Data patroli tidak ditemukan.');
        }

        $data->status = 'approved';
        $data->save();

        return redirect()->route('data_patrol.show', $id)->with('success', 'Data patroli berhasil disetujui.');
    }



    public function destroy($id)
    {
        $data = DataPatrol::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data patroli tidak ditemukan.'
            ], 404);
        }

        // Hapus gambar dari storage
        if ($data->image && Storage::disk('public')->exists($data->image)) {
            Storage::disk('public')->delete($data->image);
        }

        // Hapus data dari database
        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data patroli dan gambar berhasil dihapus.'
        ]);
    }
}
