<?php

namespace Database\Factories;

use App\Models\Accessories;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccessoriesFactory extends Factory
{
    protected $model = Accessories::class;

    public function definition(): array
    {
        $hargaBeli = $this->faker->numberBetween(10000, 50000);
        $hargaJual = $this->faker->numberBetween($hargaBeli + 5000, $hargaBeli + 80000);
        $laba = $hargaJual - $hargaBeli;

        return [
            'sku' => strtoupper('ACS-' . $this->faker->unique()->bothify('??##')),
            'nama' => ucfirst($this->faker->word()),
            'jenis' => ucfirst($this->faker->word()),
            'harga_beli' => $hargaBeli,
            'harga' => $hargaJual,
            'laba' => $laba,
            'stok' => rand(1, 100),
            'cabang_id' => \App\Models\Cabang::inRandomOrder()->first()->id,
        ];
    }
}
