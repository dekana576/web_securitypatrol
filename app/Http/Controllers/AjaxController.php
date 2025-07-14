<?php
namespace App\Http\Controllers;

use App\Models\DataPatrol;
use App\Models\SalesOffice;
use Illuminate\Support\Facades\DB;

class AjaxController extends Controller
{
    public function getSalesOfficesByRegion($regionId)
    {
        return SalesOffice::where('region_id', $regionId)->get(['id', 'sales_office_name']);
    }

    public function getPatrolChartBySalesOffice($salesOfficeId)
    {
        $labels = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        $monthlyPatrol = DataPatrol::select(
            DB::raw('MONTH(tanggal) as month'),
            DB::raw('COUNT(*) as total')
        )
        ->where('sales_office_id', $salesOfficeId)
        ->groupBy('month')
        ->get();

        $data = array_fill(0, 12, 0); // inisialisasi 12 bulan
        foreach ($monthlyPatrol as $entry) {
            $data[$entry->month - 1] = $entry->total;
        }

        $salesOffice = SalesOffice::find($salesOfficeId);

        return response()->json([
            'labels' => $labels,
            'datasets' => [[
                'label' => $salesOffice->sales_office_name ?? 'Unknown',
                'data' => $data,
                'borderWidth' => 2,
                'borderColor' => '#007bff',
                'backgroundColor' => 'rgba(0, 123, 255, 0.2)',
                'fill' => true
            ]]
        ]);
    }
}
