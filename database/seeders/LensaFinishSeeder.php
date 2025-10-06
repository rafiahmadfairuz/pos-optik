<?php

namespace Database\Seeders;

use App\Models\Cabang;
use App\Models\LensaFinish;
use App\Models\ProdukCabang;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LensaFinishSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lensas = LensaFinish::factory(30)->create();

        foreach ($lensas as $lensa) {
            $stokGudang = $lensa->stok;
            $totalCabang = 0;

            foreach (Cabang::all() as $cabang) {
                $qty = rand(0, 10);
                $totalCabang += $qty;

                ProdukCabang::create([
                    'itemable_id' => $lensa->id,
                    'itemable_type' => array_search(LensaFinish::class, Relation::morphMap()),
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
