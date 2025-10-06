<?php

namespace Database\Seeders;

use App\Models\Frame;
use App\Models\Cabang;
use App\Models\Softlen;
use App\Models\Accessories;
use App\Models\LensaFinish;
use App\Models\LensaKhusus;
use App\Models\ProdukCabang;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProdukCabangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $produkTables = [
            Frame::class,
            Accessories::class,
            Softlen::class,
            LensaFinish::class,
            LensaKhusus::class,
        ];

        foreach ($produkTables as $produkClass) {
            $produks = $produkClass::all();

            foreach ($produks as $produk) {
                foreach (Cabang::all() as $cabang) {
                    ProdukCabang::create([
                        'itemable_id' => $produk->id,
                        'itemable_type' => $produkClass,
                        'cabang_id' => $cabang->id,
                        'stok' => rand(0, 50),
                    ]);
                }
            }
        }
    }
}
