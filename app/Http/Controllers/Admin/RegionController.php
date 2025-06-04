<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Region;
use Yajra\DataTables\Facades\DataTables;

class RegionController extends Controller
{
    // Halaman utama Region
    public function index()
    {
        return view('admin.region.region');
    }

    // Ambil data untuk DataTables
    public function getdata(Request $request)
    {
        $regions = Region::query();

        return DataTables::of($regions)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '
                    <a href="' . route('region.edit', $row->id) . '" class="action-icon edit-icon"><i class="fa-solid fa-file-pen" title="Edit"></i></a>
                    <button class="action-icon delete-icon delete" data-id="' . $row->id . '"><i class="fa-solid fa-trash" title="Delete"></i></button>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    // Tampilkan form tambah region
    public function create()
    {
        return view('admin.region.add_region');
    }

    // Simpan data baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        Region::create($request->only('name'));

        return redirect()->route('region.index')->with('success', 'Region berhasil ditambahkan.');
    }

    // Tampilkan form edit
    public function edit($id)
    {
        $region = Region::findOrFail($id);
        return view('admin.region.edit_region', compact('region'));
    }

    // Proses update
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $region = Region::findOrFail($id);
        $region->update($request->only('name'));

        return redirect()->route('region.index')->with('success', 'Region berhasil diperbarui.');
    }

    // Hapus data
    public function destroy($id)
    {
        $region = Region::find($id);

        if (!$region) {
            return response()->json([
                'success' => false,
                'message' => 'Region tidak ditemukan.'
            ], 404);
        }

        $region->delete();

        return response()->json([
            'success' => true,
            'message' => 'Region berhasil dihapus.'
        ]);
    }
}
