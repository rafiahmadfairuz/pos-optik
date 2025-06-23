<?php

namespace Database\Factories;

use App\Models\Supplier;
use App\Models\Pembelian;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pembelian>
 */
class PembelianFactory extends Factory
{
    protected $model = Pembelian::class;

    public function definition(): array
    {
        return [
            'supplier_id' => Supplier::inRandomOrder()->first()?->id ?? Supplier::factory(),
            'tanggal' => now(),
            'kode' => 'PB-' . date('Ymd') . '-' . $this->faker->unique()->numberBetween(1, 9999),
            'total' => 0, 
            'retur' => $this->faker->randomElement([0, 1]),
        ];
    }
}
