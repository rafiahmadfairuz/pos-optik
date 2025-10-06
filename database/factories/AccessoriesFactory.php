<?php

namespace Database\Factories;

use App\Models\Accessories;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccessoriesFactory extends Factory
{
    protected $model = Accessories::class;

    public function definition(): array
    {
        $namaBarang = $this->faker->randomElement([
            'Lens Cleaner Spray',
            'Microfiber Cloth',
            'Frame Case',
            'Contact Lens Solution',
            'Eyewear Strap'
        ]);

        $jenis = $this->faker->randomElement([
            'Pembersih',
            'Kain',
            'Case',
            'Cairan',
            'Tali'
        ]);

        $merk = $this->faker->randomElement([
            'Zeiss',
            'Ray-Ban',
            'Essilor',
            'Miniso',
            'Luxottica'
        ]);

        $hargaBeli = $this->faker->numberBetween(20000, 100000);
        $hargaJual = $hargaBeli + $this->faker->numberBetween(10000, 80000);
        $laba = $hargaJual - $hargaBeli;

        return [
            'sku' => strtoupper('ACS-' . $this->faker->unique()->bothify('??##')),
            'nama' => $merk . ' ' . $namaBarang,
            'jenis' => $jenis,
            'harga_beli' => $hargaBeli,
            'harga' => $hargaJual,
            'laba' => $laba,
            'stok' => $this->faker->numberBetween(20, 100), 
        ];
    }
}
