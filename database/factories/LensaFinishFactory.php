<?php

namespace Database\Factories;

use App\Models\LensaFinish;
use Illuminate\Database\Eloquent\Factories\Factory;

class LensaFinishFactory extends Factory
{
    protected $model = LensaFinish::class;

    public function definition(): array
    {
        $hargaBeli = $this->faker->numberBetween(100000, 500000);
        $hargaJual = $this->faker->numberBetween($hargaBeli + 10000, $hargaBeli + 150000);
        $laba = $hargaJual - $hargaBeli;

        return [
            'sku' => strtoupper('LEN-' . $this->faker->unique()->bothify('??##')),
            'merk' => ucfirst($this->faker->word()),
            'desain' => ucfirst($this->faker->word()),
            'tipe' => ucfirst($this->faker->word()),
            'sph' => $this->faker->randomFloat(2, -10, 10),
            'cyl' => $this->faker->randomFloat(2, -5, 5),
            'add' => $this->faker->randomFloat(2, 0, 3),
            'harga_beli' => $hargaBeli,
            'harga' => $hargaJual,
            'laba' => $laba,
            'stok' => rand(1, 50),
            'cabang_id' => \App\Models\Cabang::inRandomOrder()->first()->id,
        ];
    }
}
