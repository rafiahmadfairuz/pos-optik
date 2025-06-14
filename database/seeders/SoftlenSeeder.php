<?php

namespace Database\Seeders;

use App\Models\Softlen;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SoftlenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Softlen::factory(30)->create();
    }
}
