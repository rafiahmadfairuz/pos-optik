<?php

namespace Database\Seeders;

use App\Models\Cabang;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CabangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // 1. Buat paksa Gudang Pusat dengan ID 0
        Cabang::create([
            'id'     => 0,
            'nama'   => 'Cabang 0 (Gudang Pusat)',
            'slug'   => Str::slug('Cabang 0 (Gudang Pusat)'),
            'alamat' => $faker->address,
        ]);

        // 2. Buat cabang-cabang berikutnya (ID 1, 2, 3, dst)
        $cabangs = ['Cabang 1', 'Cabang 2', 'Cabang 3', 'Cabang 4'];

        foreach ($cabangs as $index => $nama) {
            Cabang::create([
                'id'     => $index + 1, // ID mulai dari 1, 2, 3, 4
                'nama'   => $nama,
                'slug'   => Str::slug($nama),
                'alamat' => $faker->address,
            ]);
        }
    }
}
