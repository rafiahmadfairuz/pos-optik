<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Cabang;
use App\Models\Resep;
use App\Models\Orderan;
use App\Models\Asuransi;
use Illuminate\Database\Seeder;

class OrderanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Asuransi bersifat global
        $asuransiList = Asuransi::all();

        // Loop setiap cabang
        foreach (Cabang::all() as $cabang) {

            // Ambil user pada cabang tersebut
            $usersCabang = User::where('cabang_id', $cabang->id)->get();

            // Jika tidak ada user, skip
            if ($usersCabang->isEmpty()) {
                continue;
            }

            // Buat 5 orderan
            for ($i = 1; $i <= 5; $i++) {

                // Pilih user secara acak
                $user = $usersCabang->random();

                // Ambil salah satu resep milik user tersebut
                $resep = Resep::where('user_id', $user->id)
                    ->inRandomOrder()
                    ->first();

                // Jika user belum punya resep, lewati
                if (!$resep) {
                    continue;
                }

                Orderan::factory()->create([
                    'cabang_id'   => $cabang->id,
                    'user_id'     => $user->id,
                    'resep_id'    => $resep->id,
                    'staff_id'    => $resep->staff_id,
                    'asuransi_id' => $asuransiList->isNotEmpty()
                        ? $asuransiList->random()->id
                        : null,
                ]);
            }
        }
    }
}
