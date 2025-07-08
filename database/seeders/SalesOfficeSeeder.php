<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SalesOffice;
use App\Models\Region;

class SalesOfficeSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan region sudah tersedia
        $regions = Region::all();

        if ($regions->isEmpty()) {
            $this->command->info('Seeder gagal: tidak ada region ditemukan.');
            return;
        }

        $salesOffices = [
            ['sales_office_name' => 'HSO Cokroaminoto', 
            'region_id' => $regions[0]->id,
            'sales_office_address' => 'Denpasar'],
        ];

        foreach ($salesOffices as $so) {
            SalesOffice::create($so);
        }
    }
}
