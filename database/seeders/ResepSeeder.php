<?php

namespace Database\Seeders;

use App\Models\Cabang;
use App\Models\Resep;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Database\Seeder;

class ResepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cabangs = Cabang::all();

        foreach ($cabangs as $cabang) {

            $staffCabang = Staff::where('cabang_id', $cabang->id)->get();
            $usersCabang = User::where('cabang_id', $cabang->id)->get();

            // Jika cabang tidak punya staff atau user, skip
            if ($staffCabang->isEmpty() || $usersCabang->isEmpty()) {
                continue;
            }

            foreach ($usersCabang as $user) {

                Resep::factory()->create([
                    'user_id'  => $user->id,
                    'staff_id' => $staffCabang->random()->id,
                ]);
            }
        }
    }
}
