<?php
namespace App\Http\Controllers;

use App\Models\SalesOffice;

class AjaxController extends Controller
{
    public function getSalesOfficesByRegion($regionId)
    {
        return SalesOffice::where('region_id', $regionId)->get(['id', 'sales_office_name']);
    }
}
