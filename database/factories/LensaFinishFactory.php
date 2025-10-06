<?php

namespace Database\Factories;

use App\Models\LensaFinish;
use Illuminate\Database\Eloquent\Factories\Factory;

class LensaFinishFactory extends Factory
{
    protected $model = LensaFinish::class;

    public function definition(): array
    {
        $merk = $this->faker->randomElement([
            'Essilor',
            'Hoya',
            'Zeiss',
            'Kodak Lens',
            'Nikon Lens'
        ]);

        $desain = $this->faker->randomElement([
            'Single Vision',
            'Progressive',
            'Bifocal',
            'Photochromic',
            'Blue Light Filter'
        ]);

        $tipe = $this->faker->randomElement([
            'Clear',
            'Photochromic',
            'Polarized',
            'Hi-Index'
        ]);

        $hargaBeli = $this->faker->numberBetween(200000, 800000);
        $hargaJual = $hargaBeli + $this->faker->numberBetween(100000, 500000);
        $laba = $hargaJual - $hargaBeli;

        return [
            'sku' => strtoupper('LEN-' . $this->faker->unique()->bothify('??##')),
            'merk' => $merk,
            'desain' => $desain,
            'tipe' => $tipe,
            'sph' => $this->faker->randomFloat(2, -10, 10),
            'cyl' => $this->faker->randomFloat(2, -6, 6),
            'add' => $this->faker->randomFloat(2, 0, 3),
            'harga_beli' => $hargaBeli,
            'harga' => $hargaJual,
            'laba' => $laba,
            'stok' => $this->faker->numberBetween(5, 50), // stok gudang utama
        ];
    }
}
