<?php

namespace Database\Seeders;

use App\Models\Frame;
use App\Models\Softlen;
use App\Models\Supplier;
use App\Models\Pembelian;
use App\Models\Accessories;
use App\Models\LensaFinish;
use App\Models\LensaKhusus;
use App\Models\PembelianItem;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PembelianSeeder extends Seeder
{
    public function run(): void
    {
        foreach (range(1, 50) as $_) {
            $pembelian = Pembelian::factory()->create();

            $items = PembelianItem::factory()->count(10)->create([
                'pembelian_id' => $pembelian->id,
            ]);

            $total = $items->sum('subtotal');
            $pembelian->update([
                'total' => $total,
            ]);
        }
    }
}
