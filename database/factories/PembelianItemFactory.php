<?php

namespace Database\Factories;

use App\Models\Frame;
use App\Models\Softlen;
use App\Models\Accessories;
use App\Models\LensaFinish;
use App\Models\LensaKhusus;
use App\Models\PembelianItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PembelianItem>
 */
class PembelianItemFactory extends Factory
{
    protected $model = PembelianItem::class;

    public function definition(): array
    {
        $groups = [
            'frame'         => Frame::all(),
            'lensa_finish'  => LensaFinish::all(),
            'lensa_khusus'  => LensaKhusus::all(),
            'accessory'     => Accessories::all(),
            'softlens'      => Softlen::all(),
        ];

        $type = array_rand($groups);
        $collection = $groups[$type];
        $product = $collection->isNotEmpty() ? $collection->random() : null;

        $quantity = $this->faker->numberBetween(1, 3);

        $price = $product
            ? ($product->harga ?? $this->faker->numberBetween(50000, 300000))
            : $this->faker->numberBetween(50000, 300000);

        return [
            'itemable_id' => $product ? $product->id : 1,
            'itemable_type' => $type,
            'quantity' => $quantity,
            'price' => $price,
            'subtotal' => $quantity * $price,
        ];
    }
}
