<?php

namespace Database\Factories;

use App\Models\Frame;
use Illuminate\Database\Eloquent\Factories\Factory;

class FrameFactory extends Factory
{
    protected $model = Frame::class;

    public function definition(): array
    {
        $merk = $this->faker->randomElement([
            'Ray-Ban',
            'Oakley',
            'Gucci',
            'Dior',
            'Police',
            'Levi’s'
        ]);

        $tipe = $this->faker->randomElement([
            'Aviator',
            'Wayfarer',
            'Round',
            'Rectangle',
            'Cat Eye'
        ]);

        $warna = $this->faker->randomElement([
            'Hitam',
            'Cokelat',
            'Silver',
            'Gold',
            'Transparan'
        ]);

        $hargaBeli = $this->faker->numberBetween(300000, 1000000);
        $hargaJual = $hargaBeli + $this->faker->numberBetween(100000, 500000);
        $laba = $hargaJual - $hargaBeli;

        return [
            'sku' => strtoupper('FRM-' . $this->faker->unique()->bothify('??##')),
            'merk' => $merk,
            'tipe' => $tipe,
            'warna' => $warna,
            'harga_beli' => $hargaBeli,
            'harga' => $hargaJual,
            'laba' => $laba,
            'stok' => $this->faker->numberBetween(10, 100), // stok awal gudang utama
        ];
    }
}
