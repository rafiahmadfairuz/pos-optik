<?php

use App\Models\User;
use App\Models\Resep;
use App\Models\Orderan;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Models\{Accessories, Asuransi, Cabang, Staff, Frame, LensaFinish, LensaKhusus, OrderItems, Softlen,};

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Staff dan Cabang
        Staff::factory()->create(['role' => 'admin']);
        Staff::factory()->create(['role' => 'gudang']);
        Cabang::factory()->create([
            'nama' => 'Cabang 1',
            'slug' => Str::slug('Cabang 1')
        ]);

        Cabang::factory()->create([
            'nama' => 'Cabang 2',
            'slug' => Str::slug('Cabang 2')
        ]);

        Cabang::factory()->create([
            'nama' => 'Cabang 3',
            'slug' => Str::slug('Cabang 3')
        ]);

        Cabang::factory()->create([
            'nama' => 'Cabang 4',
            'slug' => Str::slug('Cabang 4')
        ]);

        // Asuransi
        $asuransis = [
            ['BPJS Level 1', 1000, 1],
            ['BPJS Level 2', 2000, 2],
            ['BPJS Level 3', 3000, 3],
            ['Asuransi Swasta A', 5000, 4],
            ['Asuransi Swasta B', 7000, 1],
            ['Asuransi Swasta C', 10000, 2],
            ['Asuransi Swasta D', 15000, 3],
            ['Asuransi Kesehatan X', 12000, 4],
            ['Asuransi Kesehatan Y', 11000, 1],
            ['Asuransi Kesehatan Z', 9000, 2],
        ];
        foreach ($asuransis as [$nama, $nominal, $cabang]) {
            Asuransi::factory()->create(['nama' => $nama, 'nominal' => $nominal, "cabang_id" => $cabang]);
        }

        $cabangIds = Cabang::pluck('id')->toArray();

          $cabangs = Cabang::all();

        foreach ($cabangs as $cabang) {
            for ($i = 0; $i < 10; $i++) {
                User::create([
                    'name' => fake()->name(),
                    'email' => fake()->unique()->safeEmail(),
                    'phone' => fake()->phoneNumber(),
                    'cabang_id' => $cabang->id,
                ]);
            }
        }

        // Frame
        for ($i = 0; $i < 20; $i++) {
            Frame::create([
                'merk' => ucfirst(fake()->word()),
                'tipe' => ucfirst(fake()->word()),
                'warna' => fake()->safeColorName(),
                'harga' => fake()->randomFloat(2, 100000, 500000),
                'stok' => rand(1, 50),
                'cabang_id' => fake()->randomElement($cabangIds),
            ]);
        }

        // Lensa Finish
        for ($i = 0; $i < 20; $i++) {
            LensaFinish::create([
                'merk' => ucfirst(fake()->word()),
                'desain' => ucfirst(fake()->word()),
                'tipe' => ucfirst(fake()->word()),
                'sph' => fake()->randomFloat(2, -10, 10),
                'cyl' => fake()->randomFloat(2, -5, 5),
                'add' => fake()->randomFloat(2, 0, 3),
                'harga' => fake()->randomFloat(2, 150000, 600000),
                'stok' => rand(1, 50),
                'cabang_id' => fake()->randomElement($cabangIds),
            ]);
        }

        // Lensa Khusus
        for ($i = 0; $i < 20; $i++) {
            LensaKhusus::create([
                'merk' => ucfirst(fake()->word()),
                'desain' => ucfirst(fake()->word()),
                'tipe' => ucfirst(fake()->word()),
                'sph' => fake()->randomFloat(2, -10, 10),
                'cyl' => fake()->randomFloat(2, -5, 5),
                'add' => fake()->randomFloat(2, 0, 3),
                'estimasi_selesai_hari' => rand(1, 7),
                'status_pesanan' => fake()->randomElement(['menunggu', 'proses', 'selesai']),
                'cabang_id' => fake()->randomElement($cabangIds),
            ]);
        }

        // Aksesoris
        for ($i = 0; $i < 20; $i++) {
            Accessories::create([
                'nama' => ucfirst(fake()->word()),
                'jenis' => ucfirst(fake()->word()),
                'harga' => fake()->randomFloat(2, 10000, 100000),
                'stok' => rand(1, 100),
                'cabang_id' => fake()->randomElement($cabangIds),
            ]);
        }

        // Softlens
        for ($i = 0; $i < 20; $i++) {
            Softlen::create([
                'merk' => ucfirst(fake()->word()),
                'tipe' => ucfirst(fake()->word()),
                'warna' => fake()->safeColorName(),
                'harga' => fake()->randomFloat(2, 50000, 300000),
                'stok' => rand(1, 100),
                'cabang_id' => fake()->randomElement($cabangIds),
            ]);
        }

    $users = User::pluck('id')->toArray();
        $staffs = Staff::pluck('id')->toArray();
        $cabangs = Cabang::pluck('id')->toArray();
        $asuransis = Asuransi::pluck('id')->toArray();

        $frames = Frame::latest()->take(10)->get();
        $lensaFinishes = LensaFinish::latest()->take(10)->get();
        $lensaKhususes = LensaKhusus::latest()->take(10)->get();
        $accessories = Accessories::latest()->take(10)->get();
        $softlens = Softlen::latest()->take(10)->get();

        for ($i = 0; $i < 40; $i++) {
            $cabangId = fake()->randomElement($cabangs);
            $staffId = fake()->randomElement($staffs);
            $userId = fake()->randomElement($users);
            $asuransiId = fake()->optional()->randomElement($asuransis);

            $orderDate = fake()->dateTimeBetween('-1 months', 'now');
            $completeDate = fake()->optional()->dateTimeBetween($orderDate, 'now');

            $order = Orderan::create([
                'user_id' => $userId,
                'cabang_id' => $cabangId,
                'order_date' => $orderDate,
                'complete_date' => $completeDate,
                'staff_id' => $staffId,
                'payment_type' => fake()->randomElement(['DP', 'pelunasan', 'asuransi']),
                'order_status' => fake()->randomElement(['pending', 'complete']),
                'payment_method' => fake()->randomElement(['cash', 'card']),
                'payment_status' => fake()->randomElement(['unpaid', 'paid']),
                'customer_paying' => $paying = fake()->randomFloat(2, 100000, 2000000),
                'perlu_dibayar' => $due = fake()->randomFloat(2, 100000, 2000000),
                'kembalian' => ($paying - $due) > 0 ? $paying - $due : null,
                'asuransi_id' => $asuransiId,
                'total' => $due,
            ]);

            // Tambah 1-5 item acak ke order
$totalOrder = 0;

foreach (range(1, rand(1, 5)) as $_) {
    // Ambil acak dari daftar produk yang sudah ditentukan
    $productGroups = [
        'frame' => $frames,
        'lensa_finish' => $lensaFinishes,
        'lensa_khusus' => $lensaKhususes,
        'accessory' => $accessories,
        'softlens' => $softlens,
    ];

    $type = array_rand($productGroups); // dapatkan string alias type (dari morphMap)
    $product = $productGroups[$type]->random();

    $quantity = rand(1, 3);
    $price = $product->harga ?? 100000;
    $subtotal = $quantity * $price;

    OrderItems::create([
        'order_id' => $order->id,
        'itemable_id' => $product->id,
        'itemable_type' => $type, // gunakan string morphMap (misalnya 'frame')
        'quantity' => $quantity,
        'price' => $price,
        'subtotal' => $subtotal,
    ]);

    $totalOrder += $subtotal;
}

$order->update(['total' => $totalOrder]);


            // Buat resep untuk order
            Resep::create([
                'orderan_id' => $order->id,
                'right_sph_d' => fake()->optional()->randomFloat(2, -10, 10),
                'right_cyl_d' => fake()->optional()->randomFloat(2, -5, 5),
                'right_axis_d' => fake()->optional()->numberBetween(0, 180),
                'right_va_d' => fake()->optional()->word(),
                'left_sph_d' => fake()->optional()->randomFloat(2, -10, 10),
                'left_cyl_d' => fake()->optional()->randomFloat(2, -5, 5),
                'left_axis_d' => fake()->optional()->numberBetween(0, 180),
                'left_va_d' => fake()->optional()->word(),
                'add_right' => fake()->optional()->randomFloat(2, 0, 3),
                'add_left' => fake()->optional()->randomFloat(2, 0, 3),
                'pd_right' => fake()->optional()->randomFloat(2, 50, 70),
                'pd_left' => fake()->optional()->randomFloat(2, 50, 70),
                'notes' => fake()->optional()->sentence(),
            ]);
        }
}
}
