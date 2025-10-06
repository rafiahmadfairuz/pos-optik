<?php

namespace Database\Factories;

use App\Models\LensaKhusus;
use Illuminate\Database\Eloquent\Factories\Factory;

class LensaKhususFactory extends Factory
{
    protected $model = LensaKhusus::class;

    public function definition(): array
    {
        $merk = $this->faker->randomElement([
            'Essilor',
            'Hoya',
            'Zeiss',
            'Nikon Lens',
            'Kodak Lens'
        ]);

        $desain = $this->faker->randomElement([
            'Progressive',
            'Bifocal',
            'Cylindrical',
            'Transition',
            'High Index'
        ]);

        $tipe = $this->faker->randomElement([
            'Clear',
            'Photochromic',
            'Polarized',
            'Hi-Index'
        ]);

        $hargaBeli = $this->faker->numberBetween(500000, 2000000);
        $hargaJual = $hargaBeli + $this->faker->numberBetween(200000, 1000000);
        $laba = $hargaJual - $hargaBeli;

        return [
            'sku' => strtoupper('LENKH-' . $this->faker->unique()->bothify('??##')),
            'merk' => $merk,
            'desain' => $desain,
            'tipe' => $tipe,
            'sph' => $this->faker->randomFloat(2, -12, 12),
            'cyl' => $this->faker->randomFloat(2, -6, 6),
            'add' => $this->faker->randomFloat(2, 0, 3),
            'harga_beli' => $hargaBeli,
            'harga' => $hargaJual,
            'laba' => $laba,
            'stok' => $this->faker->numberBetween(2, 20), // stok gudang utama
            'estimasi_selesai_hari' => $this->faker->numberBetween(2, 14),
            'status_pesanan' => $this->faker->randomElement(['menunggu', 'proses', 'selesai']),
        ];
    }
}
