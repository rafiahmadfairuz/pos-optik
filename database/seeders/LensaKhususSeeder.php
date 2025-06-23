<?php

namespace Database\Seeders;

use App\Models\Cabang;
use App\Models\LensaKhusus;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LensaKhususSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cabangs = Cabang::all();

        foreach ($cabangs as $cabang) {
            LensaKhusus::factory(2)->create(['cabang_id' => $cabang->id]);
        }

        LensaKhusus::factory(2)->create(['cabang_id' => $cabangs->random()->id]);
        LensaKhusus::factory(30)->create(['cabang_id' => null]);
    }
}
