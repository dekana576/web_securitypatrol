<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DataPatrol;
use App\Models\Region;
use App\Models\SalesOffice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
{
    $totalUser = User::count();

    $totalLaporan = DataPatrol::count();

    $laporanBelumApprove = DataPatrol::where('status', '!=', 'approved')->count();

    $monthlyPatrolData = DataPatrol::select(
        DB::raw('MONTH(tanggal) as month'),
        'sales_office_id',
        DB::raw('count(*) as total')
    )
    ->groupBy('month', 'sales_office_id')
    ->with('salesOffice:id,sales_office_name')
    ->get()
    ->groupBy('sales_office_id');

    $labels = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    $datasets = [];

    foreach ($monthlyPatrolData as $officeId => $patrols) {
        $data = array_fill(0, 12, 0);

        foreach ($patrols as $p) {
            $data[$p->month - 1] = $p->total;
        }

        $datasets[] = [
            'label' => $patrols[0]->salesOffice->sales_office_name ?? 'Unknown',
            'data' => $data,
            'borderWidth' => 2,
        ];
    }

    return view('admin.dashboard', [
        'totalUser' => $totalUser,
        'totalLaporan' => $totalLaporan,
        'laporanBelumApprove' => $laporanBelumApprove,
        'regions' => Region::all(),
        'user' => auth()->user(),
        'labels' => $labels,
        'datasets' => $datasets,
    ]);
}

}
