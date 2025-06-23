<?php

namespace Database\Seeders;

use App\Models\Frame;
use App\Models\Cabang;
use App\Models\Softlen;
use App\Models\Transfer;
use App\Models\Accessories;
use App\Models\LensaFinish;
use App\Models\LensaKhusus;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TransferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run(): void
    {
        $groups = [
            'frame' => Frame::all(),
            'lensa_finish' => LensaFinish::all(),
            'lensa_khusus' => LensaKhusus::all(),
            'accessory' => Accessories::all(),
            'softlens' => Softlen::all(),
        ];

        Cabang::factory()->count(3)->create();

        foreach (range(1, 50) as $_) {
            $type = array_rand($groups);
            $product = $groups[$type]->random();
            $quantity = rand(1, 5);
            $price = $product->harga ?? 100000;

            Transfer::create([
                'cabang_id' => Cabang::inRandomOrder()->first()->id,
                'itemable_id' => $product->id,
                'itemable_type' => $type,
                'quantity' => $quantity,
                'price' => $price,
                'retur' => rand(0, 1),

            ]);
        }
    }
}
