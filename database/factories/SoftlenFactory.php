<?php

namespace Database\Factories;

use App\Models\Softlen;
use Illuminate\Database\Eloquent\Factories\Factory;

class SoftlenFactory extends Factory
{
    protected $model = Softlen::class;

    public function definition(): array
    {
        $hargaBeli = $this->faker->numberBetween(100000, 250000);
        $hargaJual = $this->faker->numberBetween($hargaBeli + 5000, $hargaBeli + 100000);
        $laba = $hargaJual - $hargaBeli;

        return [
            'sku' => strtoupper('SFT-' . $this->faker->unique()->bothify('??##')),
            'merk' => ucfirst($this->faker->word()),
            'tipe' => ucfirst($this->faker->word()),
            'warna' => $this->faker->safeColorName(),
            'harga_beli' => $hargaBeli,
            'harga' => $hargaJual,
            'laba' => $laba,
            'stok' => rand(1, 100),
            'cabang_id' => \App\Models\Cabang::inRandomOrder()->first()->id,
        ];
    }
}
