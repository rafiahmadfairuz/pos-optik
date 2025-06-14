<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Staff;
use App\Models\Cabang;
use App\Models\Orderan;
use App\Models\Asuransi;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OrderanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (range(1, 70) as $i) {
            Orderan::factory()->create([
                'user_id' => User::inRandomOrder()->first()->id,
                'cabang_id' => Cabang::inRandomOrder()->first()->id,
                'staff_id' => Staff::inRandomOrder()->first()->id,
                'asuransi_id' => Asuransi::inRandomOrder()->first()->id,
            ]);
        }
    }
}
