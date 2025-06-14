<?php

namespace Database\Seeders;

use App\Models\Asuransi;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AsuransiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
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
            ['Jasa Raharja A', 8000, 3],
            ['Jasa Raharja B', 8500, 1],
            ['Asuransi Mandiri', 9500, 2],
            ['Prudential Basic', 12000, 4],
            ['Prudential Gold', 18000, 3],
            ['Allianz Sehat', 16000, 2],
            ['Allianz Premium', 21000, 1],
            ['Manulife Hemat', 14000, 4],
            ['Manulife Plus', 20000, 3],
            ['AXA Care', 13000, 1],
            ['AXA Platinum', 22000, 2],
            ['Takaful Sejahtera', 10000, 3],
            ['Takaful Plus', 15000, 4],
            ['Sinarmas Health', 11500, 2],
            ['Sinarmas Pro', 17000, 1],
            ['BNI Life A', 13500, 4],
            ['BNI Life B', 14500, 3],
            ['BRI Life', 15500, 1],
            ['Mega Insurance', 12500, 2],
            ['Great Eastern', 16500, 4],
        ];


        foreach ($data as [$nama, $nominal, $cabangId]) {
            Asuransi::create([
                'nama' => $nama,
                'nominal' => $nominal,
                'cabang_id' => $cabangId
            ]);
        }
    }
}
