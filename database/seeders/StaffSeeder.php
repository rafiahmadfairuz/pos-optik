<?php

namespace Database\Seeders;

use App\Models\Staff;
use App\Models\Cabang;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Staff::create([
            'name' => 'Admin Utama',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('1234'),
            'role' => 'admin',
            'cabang_id' => null,
        ]);

        Staff::create([
            'name' => 'Petugas Gudang',
            'email' => 'gudang@gmail.com',
            'password' => Hash::make('1234'),
            'role' => 'gudang_utama',
            'cabang_id' => null,
        ]);

        $cabangs = Cabang::all();

        foreach ($cabangs as $cabang) {
            // Kalau ID-nya 0 (Gudang Pusat), jangan buat staff cabang retail di sini
            if ($cabang->id == 0) {
                continue;
            }

            // Staff Cabang
            Staff::create([
                'name' => 'Staff ' . $cabang->nama,
                'email' => 'staff.' . $cabang->slug . '@gmail.com',
                'password' => Hash::make('1234'),
                'role' => 'cabang',
                'cabang_id' => $cabang->id,
            ]);

            // Gudang Cabang
            Staff::create([
                'name' => 'Gudang ' . $cabang->nama,
                'email' => 'gudang.' . $cabang->slug . '@gmail.com',
                'password' => Hash::make('1234'),
                'role' => 'gudang_cabang',
                'cabang_id' => $cabang->id,
            ]);
        }
    }
}
