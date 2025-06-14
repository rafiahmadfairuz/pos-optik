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
            'role' => 'gudang',
            'cabang_id' => null,
        ]);

        $cabangs = Cabang::all();

        foreach ($cabangs as $cabang) {
            Staff::factory(5)->create([
                'role' => 'cabang',
                'cabang_id' => $cabang->id,
            ]);
        }
    }
}
