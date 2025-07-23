<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataPatrol;
use App\Models\Region;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class DataPatrolAdminController extends Controller
{
    public function index()
    {
        $regions = Region::all();
        $user = Auth::user();
        return view('admin.data_patrol.data_patrol', compact('regions', 'user'));
    }

    public function getData(Request $request)
    {
        $query = DataPatrol::with(['region', 'salesOffice', 'checkpoint', 'user'])->latest();

        if ($request->filled('region_id')) {
            $query->where('region_id', $request->region_id);
        }

        if ($request->filled('sales_office_id')) {
            $query->where('sales_office_id', $request->sales_office_id);
        }

        if ($request->filled('year')) {
            $query->whereYear('tanggal', $request->year);
        }

        if ($request->filled('month')) {
            $query->whereMonth('tanggal', $request->month);
        }

        if ($request->filled('day')) {
            $query->whereDay('tanggal', $request->day);
        }

        if ($request->filled('kriteria')) {
            $query->where(function ($q) use ($request) {
                if ($request->kriteria === 'aman') {
                    $q->where(function ($sub) {
                        $sub->whereRaw("JSON_SEARCH(LOWER(kriteria_result), 'one', '%tidak%') IS NULL")
                            ->whereRaw("JSON_SEARCH(LOWER(kriteria_result), 'one', '%negative%') IS NULL");
                    });
                } elseif ($request->kriteria === 'tidak_aman') {
                    $q->where(function ($sub) {
                        $sub->whereRaw("JSON_SEARCH(LOWER(kriteria_result), 'one', '%tidak%') IS NOT NULL")
                            ->orWhereRaw("JSON_SEARCH(LOWER(kriteria_result), 'one', '%negative%') IS NOT NULL");
                    });
                }
            });
        }

        if ($request->filled('shift')) {
            $query->where('shift', $request->shift);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }


        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('region_name', fn($row) => $row->region->name ?? '-')
            ->addColumn('sales_office_name', fn($row) => $row->salesOffice->sales_office_name ?? '-')
            ->addColumn('checkpoint_name', fn($row) => $row->checkpoint->checkpoint_name ?? '-')
            ->addColumn('security_name', fn($row) => $row->user->name ?? '-')
            ->addColumn('tanggal', fn($row) => \Carbon\Carbon::parse($row->tanggal)->format('d M Y'))
            ->addColumn('shift', fn($row) => $row->shift ?? '-')
            ->addColumn('kriteria_result', function ($row) {
                $hasil = json_decode($row->kriteria_result, true);
                $isNegative = collect($hasil)->filter(fn($v) => stripos($v, 'tidak') !== false || stripos($v, 'negative') !== false)->isNotEmpty();
                return $isNegative
                    ? '<span class="badge bg-danger text-white">Tidak Aman</span>'
                    : '<span class="badge bg-success text-white">Aman</span>';
            })
            ->addColumn('status', function ($row) {
                return match($row->status) {
                    'submitted' => '<span class="badge bg-warning text-white">Submitted</span>',
                    'approved' => '<span class="badge bg-success text-white">Approved</span>',
                    default => '<span class="badge bg-secondary text-white">' . ucfirst($row->status) . '</span>',
                };
            })
            ->addColumn('action', function ($row) {
                return '<div class="d-flex justify-content-center align-items-center">
                <a href="' . route('data_patrol.show', $row->id) . '" class="action-icon view-icon view" title="Lihat">
                    <i class="fa-solid fa-eye"></i>
                </a>
                <a href="#" class="action-icon approve-icon approve" data-id="' . $row->id . '" title="Setujui">
                    <i class="fa-solid fa-circle-check"></i>
                </a>
                <a href="#" class="action-icon delete-icon delete" data-url="' . route('data_patrol.destroy', $row->id) . '" title="Hapus">
                    <i class="fa-solid fa-trash"></i>
                </a>
                </div>
                ';
            })
            ->rawColumns(['kriteria_result', 'status', 'action'])
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

        // Hapus semua gambar dari storage
        if ($data->image) {
            $images = json_decode($data->image, true);

            if (is_array($images)) {
                foreach ($images as $imagePath) {
                    if (Storage::disk('public')->exists($imagePath)) {
                        Storage::disk('public')->delete($imagePath);
                    }
                }
            } else {
                // Jika hanya satu gambar string (bukan array JSON)
                if (Storage::disk('public')->exists($data->image)) {
                    Storage::disk('public')->delete($data->image);
                }
            }
        }

        // Hapus data dari database
        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data patroli dan semua gambar berhasil dihapus.'
        ]);
    }

    public function printAll(Request $request)
    {
        $query = DataPatrol::with(['region', 'salesOffice', 'checkpoint', 'user'])->latest();

        // Filter seperti yang kamu lakukan di getData()
        if ($request->filled('region_id')) {
            $query->where('region_id', $request->region_id);
        }

        if ($request->filled('sales_office_id')) {
            $query->where('sales_office_id', $request->sales_office_id);
        }

        if ($request->filled('year')) {
            $query->whereYear('tanggal', $request->year);
        }

        if ($request->filled('month')) {
            $query->whereMonth('tanggal', $request->month);
        }

        if ($request->filled('day')) {
            $query->whereDay('tanggal', $request->day);
        }

        if ($request->filled('kriteria')) {
            $query->where(function ($q) use ($request) {
                if ($request->kriteria === 'aman') {
                    $q->where(function ($sub) {
                        $sub->whereRaw("JSON_SEARCH(LOWER(kriteria_result), 'one', '%tidak%') IS NULL")
                            ->whereRaw("JSON_SEARCH(LOWER(kriteria_result), 'one', '%negative%') IS NULL");
                    });
                } elseif ($request->kriteria === 'tidak_aman') {
                    $q->where(function ($sub) {
                        $sub->whereRaw("JSON_SEARCH(LOWER(kriteria_result), 'one', '%tidak%') IS NOT NULL")
                            ->orWhereRaw("JSON_SEARCH(LOWER(kriteria_result), 'one', '%negative%') IS NOT NULL");
                    });
                }
            });
        }

        if ($request->filled('shift')) {
            $query->where('shift', $request->shift);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $dataPatrols = $query->get();

        // Buat filename random
        $filename = 'DataPatrol-' . Str::random(6) . '.pdf';

        // Load blade view untuk PDF
        $pdf = Pdf::loadView('exports.data_patrols', ['dataPatrols' => $dataPatrols])
            ->setPaper('A4', 'landscape');

        return $pdf->download($filename);
    }
}
