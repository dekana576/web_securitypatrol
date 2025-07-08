<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Utama',
            'username' => 'admin',
            'nik' => '1234567890123456',
            'phone_number' => '081234567890',
            'gender' => 'male',
            'email' => 'admin@admin.com',
            'password' => Hash::make('12345678'), // ubah sesuai kebutuhan
            'region_id' => 1, // pastikan ada sales office dengan ID 1
            'sales_office_id' => 1, // pastikan ada sales office dengan ID 1
            'role' => 'admin', // sesuaikan dengan role yang kamu pakai
        ]);
    }
}
