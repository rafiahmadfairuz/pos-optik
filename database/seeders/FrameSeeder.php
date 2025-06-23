<?php

namespace Database\Seeders;

use App\Models\Frame;
use App\Models\Cabang;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FrameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cabangs = Cabang::all();

        foreach ($cabangs as $cabang) {
            Frame::factory(2)->create(['cabang_id' => $cabang->id]); // 2 x 4 = 8 produk
        }

        Frame::factory(32)->create(['cabang_id' => null]); // sisanya tanpa cabang
    }
}
