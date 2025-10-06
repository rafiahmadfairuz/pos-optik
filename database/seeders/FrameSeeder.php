<?php

namespace Database\Seeders;

use App\Models\Frame;
use App\Models\Cabang;
use App\Models\ProdukCabang;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FrameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $frames = Frame::factory(30)->create();

        foreach ($frames as $frame) {
            $stokGudang = $frame->stok;
            $totalCabang = 0;

            foreach (Cabang::all() as $cabang) {
                $qty = rand(0, 15);
                $totalCabang += $qty;

                ProdukCabang::create([
                    'itemable_id' => $frame->id,
                    'itemable_type' => array_search(Frame::class, Relation::morphMap()),
                    'cabang_id' => $cabang->id,
                    'stok' => $qty,
                ]);
            }
            $frame->update([
                'stok' => max(0, $stokGudang - $totalCabang),
            ]);
        }
    }
}
