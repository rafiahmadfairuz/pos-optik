<?php

namespace Database\Seeders;

use App\Models\Orderan;
use App\Models\OrderItems;
use App\Models\ProdukCabang;
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (Orderan::all() as $order) {

            $totalLaba = 0;

            // Setiap orderan dibuatkan 1 sampai 5 item belanjaan
            foreach (range(1, rand(1, 5)) as $_) {

                // Ambil produk cabang yang stoknya masih tersedia
                $produkCabang = ProdukCabang::where('cabang_id', $order->cabang_id)
                    ->where('stok', '>', 0)
                    ->inRandomOrder()
                    ->first();

                // Jika tidak ada produk, lanjut ke item berikutnya
                if (!$produkCabang) {
                    continue;
                }

                // Ambil data produk melalui relasi morph
                $product = $produkCabang->itemable;

                if (!$product) {
                    continue;
                }

                $quantity = rand(1, 3);
                $price = $product->harga ?? 100000;
                $subtotal = $quantity * $price;

                $hargaBeli = $product->harga_beli ?? 0;
                $labaPerItem = $price - $hargaBeli;
                $laba = $labaPerItem * $quantity;

                $totalLaba += $laba;

                OrderItems::create([
                    'order_id'      => $order->id,
                    'itemable_id'   => $product->id,
                    'itemable_type' => $produkCabang->itemable_type,
                    'quantity'      => $quantity,
                    'price'         => $price,
                    'subtotal'      => $subtotal,
                ]);

                // Jika ingin stok berkurang saat seeding, aktifkan baris ini
                // $produkCabang->decrement('stok', $quantity);
            }

            // Update total laba order
            $order->update([
                'laba_total' => $totalLaba,
            ]);
        }
    }
}
