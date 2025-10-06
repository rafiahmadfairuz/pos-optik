<?php

namespace Database\Factories;

use App\Models\Softlen;
use Illuminate\Database\Eloquent\Factories\Factory;

class SoftlenFactory extends Factory
{
    protected $model = Softlen::class;

    public function definition(): array
    {
        $merk = $this->faker->randomElement([
            'X2',
            'Bausch & Lomb',
            'Acuvue',
            'FreshKon',
            'Soflens'
        ]);

        $tipe = $this->faker->randomElement([
            'Daily',
            'Monthly',
            'Toric',
            'Color',
            'Silicone Hydrogel'
        ]);

        $warna = $this->faker->randomElement([
            'Grey',
            'Blue',
            'Brown',
            'Hazel',
            'Green'
        ]);

        $hargaBeli = $this->faker->numberBetween(80000, 200000);
        $hargaJual = $hargaBeli + $this->faker->numberBetween(20000, 100000);
        $laba = $hargaJual - $hargaBeli;

        return [
            'sku' => strtoupper('SFT-' . $this->faker->unique()->bothify('??##')),
            'merk' => $merk,
            'tipe' => $tipe,
            'warna' => $warna,
            'harga_beli' => $hargaBeli,
            'harga' => $hargaJual,
            'laba' => $laba,
            'stok' => $this->faker->numberBetween(10, 100), 
        ];
    }
}
