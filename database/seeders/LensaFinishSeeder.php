<?php

namespace Database\Seeders;

use App\Models\LensaFinish;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LensaFinishSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         LensaFinish::factory(30)->create();
    }
}
