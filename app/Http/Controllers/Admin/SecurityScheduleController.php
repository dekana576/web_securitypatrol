<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Region, SalesOffice, User, SecuritySchedule};
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SecurityScheduleController extends Controller
{
    public function index()
    {
        $regions = Region::all();
        $user = Auth::user();
        return view('admin.security_schedule.security_schedule', compact('regions', 'user'));
    }

    public function data(Request $request)
    {

        $query = SecuritySchedule::with(['region', 'salesOffice'])
            ->selectRaw('MIN(id) as id, region_id, sales_office_id, MONTH(tanggal) as bulan, YEAR(tanggal) as tahun')
            ->groupBy('region_id', 'sales_office_id', 'bulan', 'tahun');

        // Tambahkan filter jika ada input dari dropdown
        if ($request->has('region_id') && $request->region_id) {
            $query->where('region_id', $request->region_id);
        }

        if ($request->has('sales_office_id') && $request->sales_office_id) {
            $query->where('sales_office_id', $request->sales_office_id);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('region_name', fn($row) => $row->region->name ?? '-')
            ->addColumn('sales_office_name', fn($row) => $row->salesOffice->sales_office_name ?? '-')
            ->addColumn('bulan', fn($row) => Carbon::createFromDate(null, $row->bulan, 1)->translatedFormat('F'))
            ->addColumn('tahun', fn($row) => $row->tahun)
            ->addColumn('action', function ($row) {
                $regionId = $row->region_id;
                $salesOfficeId = $row->sales_office_id;
                $bulan = str_pad($row->bulan, 2, '0', STR_PAD_LEFT);
                $tahun = $row->tahun;

                return '
                    <a href="' . route('security_schedule.show', [
                        'regionId' => $regionId,
                        'salesOfficeId' => $salesOfficeId,
                        'bulan' => $bulan,
                        'tahun' => $tahun
                    ]) . '" class="action-icon view-icon view" title="Lihat">
                        <i class="fa-solid fa-eye"></i>
                    </a>
                    <a href="#" class="action-icon delete-icon delete"
                        data-region="' . $row->region_id . '"
                        data-id="' . $row->sales_office_id . '"
                        data-bulan="' . $bulan . '"
                        data-tahun="' . $tahun . '" title="Delete">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    public function create()
    {
        $user = Auth::user();
        $region = $user->region; // relasi region
        $salesOffice = $user->salesOffice; // relasi sales office

        // Ambil semua user security di sales office yang sama
        $users = User::where('sales_office_id', $salesOffice->id)
            ->where('role', 'security') // pastikan hanya role security
            ->get();

        // Ambil data bulan-tahun yang sudah ada jadwalnya
        $existingMonths = SecuritySchedule::where('region_id', $region->id)
            ->where('sales_office_id', $salesOffice->id)
            ->selectRaw('MONTH(tanggal) as bulan, YEAR(tanggal) as tahun')
            ->groupBy('bulan', 'tahun')
            ->get()
            ->map(fn($item) => sprintf('%04d-%02d', $item->tahun, $item->bulan)) // format: "2025-07"
            ->toArray();

        return view('admin.security_schedule.add_security_schedule', compact('region', 'salesOffice', 'users', 'existingMonths'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'bulan' => 'required|date_format:Y-m',
            'data' => 'required|array',
            'data.*.tanggal' => 'required|date',
            'data.*.shift' => 'required|string',
            'data.*.jam_mulai' => 'required',
            'data.*.jam_selesai' => 'required',
        ]);

        foreach ($request->data as $jadwal) {
            SecuritySchedule::create([
                'region_id' => Auth::user()->region_id,
                'sales_office_id' => Auth::user()->sales_office_id,
                'tanggal' => $jadwal['tanggal'],
                'shift' => $jadwal['shift'],
                'jam_mulai' => $jadwal['jam_mulai'],
                'jam_selesai' => $jadwal['jam_selesai'],
                'security_1_id' => $jadwal['security1'] ?? null,
                'security_2_id' => $jadwal['security2'] ?? null,
            ]);
        }

        return redirect()->route('security_schedule.index')->with('success', 'Jadwal berhasil disimpan.');
    }

    public function edit($regionId, $salesOfficeId, $bulan, $tahun)
    {
        $region = Region::findOrFail($regionId);
        $salesOffice = SalesOffice::findOrFail($salesOfficeId);
        $users = User::where('sales_office_id', $salesOfficeId)->get();

        $jadwal = SecuritySchedule::where('region_id', $regionId)
            ->where('sales_office_id', $salesOfficeId)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->get();

        return view('admin.security_schedule.edit_security_schedule', compact('region', 'salesOffice', 'bulan', 'tahun', 'users', 'jadwal'));
    }

    public function update(Request $request, $regionId, $salesOfficeId, $bulan, $tahun)
    {
        $request->validate([
            'data' => 'required|array',
        ]);

        // Format ulang bulan agar jadi angka 2 digit
        $bulan = str_pad($bulan, 2, '0', STR_PAD_LEFT);

        // Hapus jadwal lama untuk region, sales office, bulan, dan tahun tersebut
        SecuritySchedule::where('region_id', $regionId)
            ->where('sales_office_id', $salesOfficeId)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->delete();

        // Loop dan insert ulang data baru
        foreach ($request->data as $key => $item) {
            SecuritySchedule::create([
                'region_id' => $regionId,
                'sales_office_id' => $salesOfficeId,
                'tanggal' => $item['tanggal'],
                'shift' => $item['shift'],
                'jam_mulai' => $item['jam_mulai'],
                'jam_selesai' => $item['jam_selesai'],
                'security_1_id' => $item['security1'] ?? null,
                'security_2_id' => $item['security2'] ?? null,
            ]);
        }

        return redirect()->route('security_schedule.show', [
            'regionId' => $regionId,
            'salesOfficeId' => $salesOfficeId,
            'bulan' => $bulan,
            'tahun' => $tahun,
        ])->with('success', 'Jadwal berhasil diperbarui');

    }



    public function show($regionId, $salesOfficeId, $bulan, $tahun)
    {
        // Format ulang bulan agar selalu dua digit
        $bulan = str_pad($bulan, 2, '0', STR_PAD_LEFT);

        $startDate = \Carbon\Carbon::createFromDate($tahun, $bulan, 1);
        $endDate = $startDate->copy()->endOfMonth();
        $dates = collect();

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dates->push($date->copy());
        }

        $securityUsers = User::with(['region', 'salesOffice'])
            ->where('role', 'security')
            ->where('region_id', $regionId)
            ->where('sales_office_id', $salesOfficeId)
            ->get();


        $schedules = \App\Models\SecuritySchedule::where('region_id', $regionId)
            ->where('sales_office_id', $salesOfficeId)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->get();

        $dataPerSecurity = [];

        foreach ($securityUsers as $security) {
            $dataPerSecurity[$security->name] = [];

            foreach ($dates as $date) {
                $shiftCodes = $schedules->where('tanggal', $date->format('Y-m-d'))
                    ->filter(fn($s) => $s->security_1_id == $security->id || $s->security_2_id == $security->id)
                    ->pluck('shift')
                    ->map(fn($s) => match ($s) {
                        'Pagi' => 'p',
                        'Siang' => 's',
                        'Malam' => 'm',
                        'Non-Shift' => 'p8',
                        default => '',
                    })->join(', ');

                $dataPerSecurity[$security->name][$date->format('Y-m-d')] = $shiftCodes;
            }
        }

        $region = \App\Models\Region::find($regionId);

        return view('admin.security_schedule.detail_security_schedule', compact(
            'dates', 'dataPerSecurity', 'bulan', 'tahun', 'salesOfficeId', 'region'
        ));
    }

    public function destroy($regionId, $salesOfficeId, $bulan, $tahun)
    {
        $bulan = str_pad($bulan, 2, '0', STR_PAD_LEFT); // pastikan 2 digit

        try {
            DB::beginTransaction();

            SecuritySchedule::where('region_id', $regionId)
                ->where('sales_office_id', $salesOfficeId)
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->delete();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Jadwal berhasil dihapus.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal menghapus jadwal.']);
        }
    }

    
}
