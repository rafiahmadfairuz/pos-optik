<?php

namespace Database\Seeders;

use App\Models\Resep;
use App\Models\Orderan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ResepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (Orderan::all() as $order) {
            Resep::factory()->create([
                'orderan_id' => $order->id
            ]);
        }
    }
}
