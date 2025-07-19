<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Checkpoint;
use App\Models\Region;
use App\Models\SalesOffice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Spatie\Browsershot\Browsershot;

class CheckpointController extends Controller
{
    public function index()
    {
        $regions = Region::all();
        $user = Auth::user();
        return view('admin.checkpoint.checkpoint', compact('regions', 'user'));
    }

    public function data(Request $request)
    {
        $checkpoints = Checkpoint::with(['region', 'salesOffice']);

        // Tambahkan filter jika ada input dari dropdown
        if ($request->has('region_id') && $request->region_id) {
            $checkpoints->where('region_id', $request->region_id);
        }

        if ($request->has('sales_office_id') && $request->sales_office_id) {
            $checkpoints->where('sales_office_id', $request->sales_office_id);
        }

        return DataTables::of($checkpoints)
            ->addIndexColumn()
            ->addColumn('region_name', fn($row) => $row->region->name ?? '-')
            ->addColumn('sales_office_name', fn($row) => $row->salesOffice->sales_office_name ?? '-')
            ->addColumn('qr_code', fn($row) => QrCode::format('svg')->size(100)->generate($row->checkpoint_code))
            ->addColumn('action', function ($row) {
                return '
                    <a href="' . route('checkpoint.edit', $row->id) . '" class="action-icon edit-icon me-2" title="Edit">
                        <i class="fa-solid fa-file-pen"></i>
                    </a>
                    <a href="' . route('checkpoint_criteria.index', $row->id) . '" class="action-icon criteria-icon me-2" title="Create Kriteria">
                        <i class="fa-solid fa-list-check"></i>
                    </a>
                    <a href="javascript:void(0)" class="action-icon delete-icon delete" data-id="' . $row->id . '" title="Delete">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                ';
            })
            ->rawColumns(['qr_code', 'action'])
            ->make(true);
    }


    public function create()
    {
        $regions = Region::all();
        return view('admin.checkpoint.add_checkpoint', compact('regions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'region_id' => 'required|exists:regions,id',
            'sales_office_id' => 'required|exists:sales_offices,id',
            'checkpoint_name' => 'required|string|max:255',
        ]);

        $code = strtoupper(Str::random(8));

        // Simpan QR Code ke file SVG
        $qrSvg = QrCode::format('svg')->size(200)->generate($code);

        // Path file di storage
        $filePath = "public/qrcodes/{$code}.svg";

        // Simpan ke storage
        Storage::put($filePath, $qrSvg);

        // Simpan checkpoint ke DB
        Checkpoint::create([
            'region_id' => $request->region_id,
            'sales_office_id' => $request->sales_office_id,
            'checkpoint_name' => $request->checkpoint_name,
            'checkpoint_code' => $code,
        ]);

        return redirect()->route('checkpoint.index')->with('success', 'Checkpoint berhasil ditambahkan');
    }

    public function edit($id)
    {
        $checkpoint = Checkpoint::findOrFail($id);
        $regions = Region::all();
        $salesOffices = SalesOffice::where('region_id', $checkpoint->region_id)->get();

        return view('admin.checkpoint.edit_checkpoint', compact('checkpoint', 'regions', 'salesOffices'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'region_id' => 'required|exists:regions,id',
            'sales_office_id' => 'required|exists:sales_offices,id',
            'checkpoint_name' => 'required|string|max:255',
        ]);

        $checkpoint = Checkpoint::findOrFail($id);
        $checkpoint->update([
            'region_id' => $request->region_id,
            'sales_office_id' => $request->sales_office_id,
            'checkpoint_name' => $request->checkpoint_name,
        ]);

        return redirect()->route('checkpoint.index')->with('success', 'Checkpoint berhasil diupdate');
    }


    public function destroy($id)
    {
        $checkpoint = Checkpoint::find($id);

        if (!$checkpoint) {
            return response()->json([
                'success' => false,
                'message' => 'Checkpoint tidak ditemukan.'
            ], 404);
        }

        $checkpoint->delete();

        return response()->json([
            'success' => true,
            'message' => 'Checkpoint berhasil dihapus.'
        ]);
    }

    // AJAX: Sales Office by Region
    public function getSalesOfficesByRegion($regionId)
    {
        $salesOffices = SalesOffice::where('region_id', $regionId)->get();
        return response()->json($salesOffices);
    }

    public function printAll(Request $request)
    {
        $query = Checkpoint::with(['region', 'salesOffice']);

        // Filter berdasarkan region dan sales office jika ada
        if ($request->filled('region_id')) {
            $query->where('region_id', $request->region_id);
        }

        if ($request->filled('sales_office_id')) {
            $query->where('sales_office_id', $request->sales_office_id);
        }

        $checkpoints = $query->get();

        // Render view ke HTML
        $html = view('exports.checkpoints', compact('checkpoints'))->render();

        // File PDF sementara
        $filename = 'QRcode-' . Str::random(6) . '.pdf';
        $filepath = storage_path('app/public/' . $filename);

        // Generate PDF
        Browsershot::html($html)
            ->waitUntilNetworkIdle()
            ->timeout(60)
            ->format('A4')
            ->savePdf($filepath);

        // Stream PDF dan hapus file setelah selesai dikirim
        return response()->streamDownload(function () use ($filepath) {
            echo file_get_contents($filepath);
            // Hapus file setelah dikirim
            File::delete($filepath);
        }, $filename);
    }
}
