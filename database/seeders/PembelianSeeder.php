<?php

namespace Database\Seeders;

use App\Models\Frame;
use App\Models\Softlen;
use App\Models\Supplier;
use App\Models\Pembelian;
use App\Models\Accessories;
use App\Models\LensaFinish;
use App\Models\LensaKhusus;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PembelianSeeder extends Seeder
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

        Supplier::factory()->count(5)->create();

        foreach (range(1, 50) as $_) {
            $type = array_rand($groups);
            $product = $groups[$type]->random();
            $quantity = rand(1, 3);
            $price = $product->harga ?? 100000;
            $subtotal = $price * $quantity;

            Pembelian::create([
                'supplier_id' => Supplier::inRandomOrder()->first()->id,
                'itemable_id' => $product->id,
                'itemable_type' => $type,
                'quantity' => $quantity,
                'price' => $price,
                'subtotal' => $subtotal,
                'total' => $subtotal,
                'retur' => rand(0, 1),

            ]);
        }
    }
}
