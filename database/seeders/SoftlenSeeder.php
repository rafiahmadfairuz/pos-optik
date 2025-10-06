<?php

namespace Database\Seeders;

use App\Models\Cabang;
use App\Models\Softlen;
use App\Models\ProdukCabang;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SoftlenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $softlens = Softlen::factory(30)->create();

        foreach ($softlens as $sl) {
            $stokGudang = $sl->stok;
            $totalCabang = 0;

            foreach (Cabang::all() as $cabang) {
                $qty = rand(0, 15);
                $totalCabang += $qty;

                ProdukCabang::create([
                    'itemable_id' => $sl->id,
                    'itemable_type' => array_search(Softlen::class, Relation::morphMap()),
                    'cabang_id' => $cabang->id,
                    'stok' => $qty,
                ]);
            }

            $sl->update([
                'stok' => max(0, $stokGudang - $totalCabang),
            ]);
        }
    }
}
