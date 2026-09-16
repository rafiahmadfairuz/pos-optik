<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Cabang;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Cabang::all() as $cabang) {
            User::factory(10)->create([
                'cabang_id' => $cabang->id,
            ]);
        }
    }
}
