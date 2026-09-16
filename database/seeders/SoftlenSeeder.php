<?php

namespace Database\Seeders;

use App\Models\Cabang;
use App\Models\Softlen;
use App\Models\ProdukCabang;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Relations\Relation;

class SoftlenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $softlens = Softlen::factory(5)->create();

        $morphType = array_search(Softlen::class, Relation::morphMap()) ?: Softlen::class;

        foreach ($softlens as $sl) {
            foreach (Cabang::all() as $cabang) {

                // Kalau cabang pusat (ID 0), stok digedein buat gudang utama
                if ($cabang->id == 0) {
                    $qty = rand(10, 50);
                } else {
                    // Cabang lain random, biar ada yang stoknya 0 sesuai mau client
                    $qty = rand(0, 10);
                }

                ProdukCabang::create([
                    'itemable_id' => $sl->id,
                    'itemable_type' => $morphType,
                    'cabang_id' => $cabang->id,
                    'stok' => $qty,
                ]);
            }
        }
    }
}
