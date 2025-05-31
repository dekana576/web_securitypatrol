<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Region;
use Yajra\DataTables\Facades\DataTables;

class RegionController extends Controller
{
    public function index(Request $request)
    {

        return view('admin.region.region');
    }
    
    public function getdata(Request $request)
    {
        $regions = Region::query();

        return DataTables::of($regions)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '
                    <button class="btn btn-sm btn-primary edit" data-id="'.$row->id.'">Edit</button>
                    <button class="btn btn-sm btn-danger delete" data-id="'.$row->id.'">Delete</button>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    
    
    public function create()
    {
        return view('admin.region.add_region');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Region::create($request->only('name'));
        return redirect()->route('region.index')->with('success', 'Region berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $region = Region::findOrFail($id);
        $region->update($request->only('name'));
        return response()->json(['message' => 'Region updated']);
    }

    public function destroy($id)
    {
        $region = Region::findOrFail($id);
        $region->delete();
        return response()->json(['message' => 'Region deleted']);
    }
}