<?php

namespace Database\Factories;

use App\Models\OrderItems;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemsFactory extends Factory
{
    protected $model = OrderItems::class;

    public function definition(): array
    {
        $quantity = rand(1, 3);
        $price = $this->faker->numberBetween(50000, 300000);

        return [
            'order_id' => \App\Models\Orderan::factory(),
            'itemable_id' => 1, 
            'itemable_type' => 'frame',
            'quantity' => $quantity,
            'price' => $price,
            'subtotal' => $price * $quantity,
        ];
    }
}
