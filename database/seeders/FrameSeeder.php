<?php

namespace Database\Seeders;

use App\Models\Cabang;
use App\Models\Frame;
use App\Models\ProdukCabang;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Relations\Relation;

class FrameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $frames = Frame::factory(5)->create();

        $morphType = array_search(Frame::class, Relation::morphMap()) ?: Frame::class;

        foreach ($frames as $frame) {
            foreach (Cabang::all() as $cabang) {

                // Cek apakah ini cabang pusat (sesuaikan kondisi ID pusat lu, misal id == 1 atau id == 0)
                if ($cabang->id == 1) {
                    $qty = rand(10, 50); // Pusat dikasih stok lebih banyak buat gudang utama
                } else {
                    $qty = rand(0, 10);  // Cabang lain random, bisa ada yang 0 (kosong) sesuai mau client
                }

                ProdukCabang::create([
                    'itemable_id' => $frame->id,
                    'itemable_type' => $morphType,
                    'cabang_id' => $cabang->id,
                    'stok' => $qty,
                ]);
            }
        }
    }
}
