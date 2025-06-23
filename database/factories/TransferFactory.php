<?php

namespace Database\Factories;

use App\Models\Cabang;
use App\Models\Transfer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transfer>
 */
class TransferFactory extends Factory
{
    protected $model = Transfer::class;

    public function definition(): array
    {
        return [
            'cabang_id' => Cabang::inRandomOrder()->first()?->id ?? Cabang::factory(),
            'tanggal' => now(),
            'kode' => 'TF-' . date('Ymd') . '-' . $this->faker->unique()->numberBetween(1, 1000),
            'retur' => $this->faker->boolean,
        ];
    }
}
