<?php

namespace Database\Seeders;

use App\Models\Frame;
use App\Models\Cabang;
use App\Models\Softlen;
use App\Models\Transfer;
use App\Models\Accessories;
use App\Models\LensaFinish;
use App\Models\LensaKhusus;
use App\Models\TransferItem;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TransferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        foreach (range(1, 50) as $_) {
            $transfer = Transfer::factory()->create();

            TransferItem::factory()->count(10)->create([
                'transfer_id' => $transfer->id,
            ]);
        }
    }
}
