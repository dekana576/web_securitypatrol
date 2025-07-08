<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Region;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        $regions = [
            ['name' => 'Bali'],
        ];

        foreach ($regions as $region) {
            Region::create($region);
        }
    }
}
