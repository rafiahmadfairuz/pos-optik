<?php

namespace Database\Seeders;

use App\Models\Cabang;
use App\Models\Accessories;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AccessoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cabangs = Cabang::all();

        foreach ($cabangs as $cabang) {
            Accessories::factory(2)->create(['cabang_id' => $cabang->id]);
        }

        Accessories::factory(2)->create(['cabang_id' => $cabangs->random()->id]);
        Accessories::factory(30)->create(['cabang_id' => null]);
    }
}
