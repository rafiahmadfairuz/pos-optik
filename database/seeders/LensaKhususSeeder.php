<?php

namespace Database\Seeders;

use App\Models\Cabang;
use App\Models\LensaKhusus;
use App\Models\ProdukCabang;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Relations\Relation;

class LensaKhususSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lensas = LensaKhusus::factory(5)->create();

        $morphType = array_search(LensaKhusus::class, Relation::morphMap()) ?: LensaKhusus::class;

        foreach ($lensas as $lensa) {
            foreach (Cabang::all() as $cabang) {

                // Kalau cabang pusat (ID 0), stok digedein buat gudang utama
                if ($cabang->id == 0) {
                    $qty = rand(10, 50);
                } else {
                    // Cabang lain random, biar ada yang stoknya 0 sesuai mau client
                    $qty = rand(0, 5);
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
