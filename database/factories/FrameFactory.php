<?php

namespace Database\Factories;

use App\Models\Frame;
use Illuminate\Database\Eloquent\Factories\Factory;

class FrameFactory extends Factory
{
    protected $model = Frame::class;

    public function definition(): array
    {
        $hargaBeli = $this->faker->randomFloat(2, 100000, 400000);
        $hargaJual = $this->faker->randomFloat(2, $hargaBeli + 10000, $hargaBeli + 150000);
        $laba = $hargaJual - $hargaBeli;

        return [
            'merk' => ucfirst($this->faker->word()),
            'tipe' => ucfirst($this->faker->word()),
            'warna' => $this->faker->safeColorName(),
            'harga_beli' => $hargaBeli,
            'harga' => $hargaJual,
            'laba' => $laba,
            'stok' => rand(1, 50),
            'cabang_id' => null,
        ];
    }
}
