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
                $isNegative = collect($hasil)->filter(fn($value) => stripos($value, 'tidak') !== false || stripos($value, 'negative') !== false)->isNotEmpty();
                $text = implode(', ', $hasil);
                return $isNegative ? "<span class='text-danger'>{$text}</span>" : $text;
            })
            ->addColumn('status', fn($row) => ucfirst($row->status))
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
            ->rawColumns(['kriteria_result', 'action'])
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
