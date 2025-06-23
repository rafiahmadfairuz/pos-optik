<?php

namespace Database\Factories;

use App\Models\Cabang;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transfer>
 */
class TransferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = $this->faker->numberBetween(1, 5);
        $price = $this->faker->randomFloat(2, 30000, 150000);

        return [
            'cabang_id' => Cabang::inRandomOrder()->first()?->id ?? Cabang::factory(),
            'itemable_id' => 1, // override nanti
            'itemable_type' => 'frame',
            'quantity' => $quantity,
            'price' => $price,
            'retur' => $this->faker->randomElement([0, 1]),

        ];
    }
}
