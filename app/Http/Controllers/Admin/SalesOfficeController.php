<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SalesOffice;
use App\Models\Region;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SalesOfficeController extends Controller
{
    public function index()
    {
        $salesOffices = SalesOffice::with('region')->get();
        return view('admin.sales_office.sales_office', compact('salesOffices'));
    }

    public function create()
    {
        $regions = Region::all();
        return view('admin.sales_office.add_sales_office', compact('regions'));
    }

    public function getdata(Request $request)
    {
        $salesOffices = SalesOffice::with('region');

        return DataTables::of($salesOffices)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return '
                    <a href="' . route('sales_office.edit', $row->id) . '" class="action-icon edit-icon"><i class="fa-solid fa-file-pen" title="Edit"></i></a>
                    <a href="" class="action-icon delete-icon delete" data-id="' . $row->id . '"><i class="fa-solid fa-trash" title="Delete"></i></a>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'region_id' => 'required|exists:regions,id',
            'sales_office_name' => 'required|string|max:255',
            'sales_office_address' => 'required|string'
        ]);

        SalesOffice::create($request->all());

        return redirect()->route('sales_office.index')->with('success', 'Sales Office berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $salesOffice = SalesOffice::findOrFail($id);
        $regions = Region::all();

        return view('admin.sales_office.edit_sales_office', compact('salesOffice', 'regions'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'region_id' => 'required|exists:regions,id',
            'sales_office_name' => 'required|string|max:255',
            'sales_office_address' => 'required|string',
        ]);

        $salesOffice = SalesOffice::findOrFail($id);
        $salesOffice->update($request->only(['region_id', 'sales_office_name', 'sales_office_address']));

        return redirect()->route('sales_office.index')->with('success', 'Sales Office berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $SalesOffice = SalesOffice::find($id);

        if (!$SalesOffice) {
            return response()->json([
                'success' => false,
                'message' => 'Region tidak ditemukan.'
            ], 404);
        }

        $SalesOffice->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sales Office berhasil dihapus.'
        ]);
    }
}
