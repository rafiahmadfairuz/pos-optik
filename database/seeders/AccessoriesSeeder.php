<?php

namespace Database\Seeders;

use App\Models\Cabang;
use App\Models\Accessories;
use App\Models\ProdukCabang;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AccessoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accessories = Accessories::factory(30)->create();

        foreach ($accessories as $acc) {
            $stokGudang = $acc->stok;
            $totalCabang = 0;

            foreach (Cabang::all() as $cabang) {

                $qty = rand(0, 10);
                $totalCabang += $qty;

                ProdukCabang::create([
                    'itemable_id' => $acc->id,
                    'itemable_type' => array_search(Accessories::class, Relation::morphMap()),
                    'cabang_id' => $cabang->id,
                    'stok' => $qty,
                ]);
            }
            $acc->update([
                'stok' => max(0, $stokGudang - $totalCabang),
            ]);
        }
    }
}
