<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Region, SalesOffice, SecuritySchedule, User};
use Illuminate\Http\Request;

class SecurityScheduleController extends Controller
{
    public function index(Request $request)
    {
        $regions = Region::all();
        $salesOffices = [];

        $selectedOffice = null;
        $jadwals = [];

        if ($request->region_id) {
            $salesOffices = SalesOffice::where('region_id', $request->region_id)->get();
        }

        if ($request->region_id && $request->sales_office_id) {
            $selectedOffice = SalesOffice::with('security_schedule')->find($request->sales_office_id);
            $jadwals = SecuritySchedule::where('sales_office_id', $request->sales_office_id)->get();
        }

        $jadwals = SecuritySchedule::with([
            'seninUser', 'selasaUser', 'rabuUser', 'kamisUser',
            'jumatUser', 'sabtuUser', 'mingguUser'
        ])->where('sales_office_id', $request->sales_office_id)->get();


        return view('admin.security_schedule.security_schedule', compact('regions', 'salesOffices', 'selectedOffice', 'jadwals'));
    }


    public function create(Request $request)
    {
        $salesOfficeId = $request->sales_office_id;
        $salesOffice = SalesOffice::findOrFail($salesOfficeId);
        $regionId = $salesOffice->region_id;
        $users = User::where('sales_office_id', $salesOfficeId)->get();

        return view('admin.security_schedule.add_security_schedule', [
            'regionId' => $regionId,
            'salesOfficeId' => $salesOfficeId,
            'users' => $users,
            'isEdit' => false
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'region_id' => 'required|exists:regions,id',
            'sales_office_id' => 'required|exists:sales_offices,id',
            'shift.*' => 'required|string',
            'jam_mulai.*' => 'required|string',
            'jam_berakhir.*' => 'required|string',
        ]);

        // Hapus jadwal lama terlebih dahulu jika ada (prevent duplicate)
        SecuritySchedule::where('sales_office_id', $request->sales_office_id)->delete();

        for ($i = 0; $i < count($request->shift); $i++) {
            SecuritySchedule::create([
                'region_id' => $request->region_id,
                'sales_office_id' => $request->sales_office_id,
                'shift' => $request->shift[$i],
                'jam_mulai' => $request->jam_mulai[$i],
                'jam_berakhir' => $request->jam_berakhir[$i],
                'senin' => $request->senin[$i] ?? null,
                'selasa' => $request->selasa[$i] ?? null,
                'rabu' => $request->rabu[$i] ?? null,
                'kamis' => $request->kamis[$i] ?? null,
                'jumat' => $request->jumat[$i] ?? null,
                'sabtu' => $request->sabtu[$i] ?? null,
                'minggu' => $request->minggu[$i] ?? null,
            ]);
        }

        return redirect()->route('security_schedule.index', ['region_id' => $request->region_id, 'sales_office_id' => $request->sales_office_id])
            ->with('success', 'Jadwal security berhasil disimpan.');
    }

    public function edit(Request $request)
    {
        $salesOfficeId = $request->sales_office_id;
        $salesOffice = SalesOffice::findOrFail($salesOfficeId);
        $regionId = $salesOffice->region_id;
        $users = User::where('sales_office_id', $salesOfficeId)->get();
        $jadwals = SecuritySchedule::where('sales_office_id', $salesOfficeId)->get();

        return view('admin.security_schedule.edit_security_schedule', [
            'regionId' => $regionId,
            'salesOfficeId' => $salesOfficeId,
            'users' => $users,
            'jadwals' => $jadwals,
            'isEdit' => true
        ]);
    }
}
