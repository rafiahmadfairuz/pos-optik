<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\{Accessories, Asuransi, Cabang, Staff, Frame, LensaFinish, LensaKhusus,  Softlen,};

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Staff dan Cabang
        Staff::factory()->create(['role' => 'admin']);
        Staff::factory()->create(['role' => 'gudang']);
        Cabang::factory()->create([
            'nama' => 'Cabang 1',
            'slug' => Str::slug('Cabang 1')
        ]);

        Cabang::factory()->create([
            'nama' => 'Cabang 2',
            'slug' => Str::slug('Cabang 2')
        ]);

        Cabang::factory()->create([
            'nama' => 'Cabang 3',
            'slug' => Str::slug('Cabang 3')
        ]);

        Cabang::factory()->create([
            'nama' => 'Cabang 4',
            'slug' => Str::slug('Cabang 4')
        ]);

        // Asuransi
        $asuransis = [
            ['BPJS Level 1', 1000, 1],
            ['BPJS Level 2', 2000, 2],
            ['BPJS Level 3', 3000, 3],
            ['Asuransi Swasta A', 5000, 4],
            ['Asuransi Swasta B', 7000, 1],
            ['Asuransi Swasta C', 10000, 2],
            ['Asuransi Swasta D', 15000, 3],
            ['Asuransi Kesehatan X', 12000, 4],
            ['Asuransi Kesehatan Y', 11000, 1],
            ['Asuransi Kesehatan Z', 9000, 2],
        ];
        foreach ($asuransis as [$nama, $nominal, $cabang]) {
            Asuransi::factory()->create(['nama' => $nama, 'nominal' => $nominal, "cabang_id" => $cabang]);
        }

        $cabangIds = Cabang::pluck('id')->toArray();

        // Frame
        for ($i = 0; $i < 20; $i++) {
            Frame::create([
                'merk' => ucfirst(fake()->word()),
                'tipe' => ucfirst(fake()->word()),
                'warna' => fake()->safeColorName(),
                'harga' => fake()->randomFloat(2, 100000, 500000),
                'stok' => rand(1, 50),
                'cabang_id' => fake()->randomElement($cabangIds),
            ]);
        }

        // Lensa Finish
        for ($i = 0; $i < 20; $i++) {
            LensaFinish::create([
                'merk' => ucfirst(fake()->word()),
                'desain' => ucfirst(fake()->word()),
                'tipe' => ucfirst(fake()->word()),
                'sph' => fake()->randomFloat(2, -10, 10),
                'cyl' => fake()->randomFloat(2, -5, 5),
                'add' => fake()->randomFloat(2, 0, 3),
                'harga' => fake()->randomFloat(2, 150000, 600000),
                'stok' => rand(1, 50),
                'cabang_id' => fake()->randomElement($cabangIds),
            ]);
        }

        // Lensa Khusus
        for ($i = 0; $i < 20; $i++) {
            LensaKhusus::create([
                'merk' => ucfirst(fake()->word()),
                'desain' => ucfirst(fake()->word()),
                'tipe' => ucfirst(fake()->word()),
                'sph' => fake()->randomFloat(2, -10, 10),
                'cyl' => fake()->randomFloat(2, -5, 5),
                'add' => fake()->randomFloat(2, 0, 3),
                'estimasi_selesai_hari' => rand(1, 7),
                'status_pesanan' => fake()->randomElement(['menunggu', 'proses', 'selesai']),
                'cabang_id' => fake()->randomElement($cabangIds),
            ]);
        }

        // Aksesoris
        for ($i = 0; $i < 20; $i++) {
            Accessories::create([
                'nama' => ucfirst(fake()->word()),
                'jenis' => ucfirst(fake()->word()),
                'harga' => fake()->randomFloat(2, 10000, 100000),
                'stok' => rand(1, 100),
                'cabang_id' => fake()->randomElement($cabangIds),
            ]);
        }

        // Softlens
        for ($i = 0; $i < 20; $i++) {
            Softlen::create([
                'merk' => ucfirst(fake()->word()),
                'tipe' => ucfirst(fake()->word()),
                'warna' => fake()->safeColorName(),
                'harga' => fake()->randomFloat(2, 50000, 300000),
                'stok' => rand(1, 100),
                'cabang_id' => fake()->randomElement($cabangIds),
            ]);
        }
    }
}
