<?php

namespace Database\Seeders;

use App\Models\Cabang;
use App\Models\Softlen;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SoftlenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cabangs = Cabang::all();

        foreach ($cabangs as $cabang) {
            Softlen::factory(2)->create(['cabang_id' => $cabang->id]);
        }

        Softlen::factory(2)->create(['cabang_id' => $cabangs->random()->id]);
        Softlen::factory(30)->create(['cabang_id' => null]);
    }
}
