<?php

namespace Database\Seeders;

use App\Models\Cabang;
use App\Models\LensaFinish;
use App\Models\ProdukCabang;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Relations\Relation;

class LensaFinishSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lensas = LensaFinish::factory(5)->create();

        $morphType = array_search(LensaFinish::class, Relation::morphMap()) ?: LensaFinish::class;

        foreach ($lensas as $lensa) {
            foreach (Cabang::all() as $cabang) {

                // Kalau cabang pusat (ID 0), stok digedein buat gudang utama
                if ($cabang->id == 0) {
                    $qty = rand(10, 50);
                } else {
                    // Cabang lain random, biar ada yang stoknya 0 sesuai mau client
                    $qty = rand(0, 10);
                }

                ProdukCabang::create([
                    'itemable_id' => $lensa->id,
                    'itemable_type' => $morphType,
                    'cabang_id' => $cabang->id,
                    'stok' => $qty,
                ]);
            }
        }
    }
}
