<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pembelian>
 */
class PembelianFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = $this->faker->numberBetween(1, 3);
        $price = $this->faker->randomFloat(2, 50000, 300000);
        $subtotal = $quantity * $price;

        return [
            'supplier_id' => Supplier::inRandomOrder()->first()?->id ?? Supplier::factory(),
            'itemable_id' => 1, // akan dioverride di seeder
            'itemable_type' => 'frame', // default
            'quantity' => $quantity,
            'price' => $price,
            'subtotal' => $subtotal,
            'total' => $subtotal,
            'retur' => $this->faker->randomElement([0, 1]),
        ];
    }
}
