<?php

namespace Database\Seeders;

use App\Models\Frame;
use App\Models\Orderan;
use App\Models\Softlen;
use App\Models\OrderItems;
use App\Models\Accessories;
use App\Models\LensaFinish;
use App\Models\LensaKhusus;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $groups = [
            'frame'         => Frame::all(),
            'lensa_finish'  => LensaFinish::all(),
            'lensa_khusus'  => LensaKhusus::all(),
            'accessory'     => Accessories::all(),
            'softlens'      => Softlen::all(),
        ];

        foreach (Orderan::all() as $order) {
            $totalLaba = 0;

            foreach (range(1, rand(1, 5)) as $_) {
                $type = array_rand($groups);
                $collection = $groups[$type];

                // Pastikan collection tidak kosong
                if ($collection->isEmpty()) {
                    continue; // Lewati kalau kosong
                }

                $product = $collection->random();

                $quantity = rand(1, 3);
                $price = $product->harga ?? 100000;
                $subtotal = $quantity * $price;

                $labaPerItem = ($product->harga ?? 0) - ($product->harga_beli ?? 0);
                $laba = $labaPerItem * $quantity;

                $totalLaba += $laba;

                OrderItems::create([
                    'order_id'      => $order->id,
                    'itemable_id'   => $product->id,
                    'itemable_type' => $type,
                    'quantity'      => $quantity,
                    'price'         => $price,
                    'subtotal'      => $subtotal,
                ]);
            }

            $order->update([
                'laba_total' => $totalLaba,
            ]);
        }
    }
}
