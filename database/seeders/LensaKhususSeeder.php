<?php

namespace Database\Seeders;

use App\Models\Cabang;
use App\Models\LensaKhusus;
use App\Models\ProdukCabang;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LensaKhususSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lensas = LensaKhusus::factory(30)->create();

        foreach ($lensas as $lensa) {
            $stokGudang = $lensa->stok;
            $totalCabang = 0;

            foreach (Cabang::all() as $cabang) {
                $qty = rand(0, 5);
                $totalCabang += $qty;

                ProdukCabang::create([
                    'itemable_id' => $lensa->id,
                    'itemable_type' => array_search(LensaKhusus::class, Relation::morphMap()),
                    'cabang_id' => $cabang->id,
                    'stok' => $qty,
                ]);
            }

            $lensa->update([
                'stok' => max(0, $stokGudang - $totalCabang),
            ]);
        }
    }
}
