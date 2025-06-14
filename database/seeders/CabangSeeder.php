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

        $cabangs = ['Cabang 1', 'Cabang 2', 'Cabang 3', 'Cabang 4'];

        foreach ($cabangs as $nama) {
            Cabang::create([
                'nama' => $nama,
                'slug' => Str::slug($nama),
                'alamat' => $faker->address, 
            ]);
        }
    }
}
