<?php

namespace Database\Seeders;

use App\Models\Asuransi;
use App\Models\Cabang;
use App\Models\Staff;
use App\Models\User;
use Database\Factories\AsurasnsiFactory;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();


        Staff::factory()->create(['role' => 'admin']);
        Staff::factory()->create(['role' => 'gudang']);
        Cabang::factory()->create(['nama' => 'Cabang 1']);
        Cabang::factory()->create(['nama' => 'Cabang 2']);
        Cabang::factory()->create(['nama' => 'Cabang 3']);
        Cabang::factory()->create(['nama' => 'Cabang 4']);

        Asuransi::factory()->create(['nama' => 'BPJS Level 1', 'nominal' => 1000]);
        Asuransi::factory()->create(['nama' => 'BPJS Level 2', 'nominal' => 2000]);
        Asuransi::factory()->create(['nama' => 'BPJS Level 3', 'nominal' => 3000]);
        Asuransi::factory()->create(['nama' => 'Asuransi Swasta A', 'nominal' => 5000]);
        Asuransi::factory()->create(['nama' => 'Asuransi Swasta B', 'nominal' => 7000]);
        Asuransi::factory()->create(['nama' => 'Asuransi Swasta C', 'nominal' => 10000]);
        Asuransi::factory()->create(['nama' => 'Asuransi Swasta D', 'nominal' => 15000]);
        Asuransi::factory()->create(['nama' => 'Asuransi Kesehatan X', 'nominal' => 12000]);
        Asuransi::factory()->create(['nama' => 'Asuransi Kesehatan Y', 'nominal' => 11000]);
        Asuransi::factory()->create(['nama' => 'Asuransi Kesehatan Z', 'nominal' => 9000]);
    }
}
