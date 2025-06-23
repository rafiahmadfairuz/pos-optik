<?php

namespace Database\Seeders;

use App\Models\Cabang;
use App\Models\LensaFinish;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LensaFinishSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cabangs = Cabang::all();

        foreach ($cabangs as $cabang) {
            LensaFinish::factory(2)->create(['cabang_id' => $cabang->id]);
        }

        LensaFinish::factory(2)->create([
            'cabang_id' => $cabangs->random()->id
        ]);

        LensaFinish::factory(30)->create(['cabang_id' => null]);
    }
}
