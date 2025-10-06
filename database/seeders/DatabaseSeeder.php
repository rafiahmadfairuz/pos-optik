<?php

use App\Models\ProdukCabang;
use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\FrameSeeder;
use Database\Seeders\ResepSeeder;
use Database\Seeders\StaffSeeder;
use Database\Seeders\CabangSeeder;
use Database\Seeders\OrderanSeeder;
use Database\Seeders\SoftlenSeeder;
use Database\Seeders\AsuransiSeeder;
use Database\Seeders\AccessoriesSeeder;
use Database\Seeders\LensaFinishSeeder;
use Database\Seeders\LensaKhususSeeder;
use Database\Seeders\OrderItemSeeder;
use Database\Seeders\PembelianSeeder;
use Database\Seeders\SupplierSeeder;
use Database\Seeders\TransferSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CabangSeeder::class,
            StaffSeeder::class,
            UserSeeder::class,
            AsuransiSeeder::class,
            FrameSeeder::class,
            LensaFinishSeeder::class,
            LensaKhususSeeder::class,
            AccessoriesSeeder::class,
            SoftlenSeeder::class,
            OrderanSeeder::class,
            OrderItemSeeder::class,
            ResepSeeder::class,
            SupplierSeeder::class,
            PembelianSeeder::class,
            TransferSeeder::class
        ]);
    }
}
