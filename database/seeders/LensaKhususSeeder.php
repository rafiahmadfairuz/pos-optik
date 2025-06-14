<?php

namespace Database\Seeders;

use App\Models\LensaKhusus;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LensaKhususSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         LensaKhusus::factory(30)->create();
    }
}
