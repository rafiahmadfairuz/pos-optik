<?php

namespace Database\Factories;

use App\Models\Orderan;
use App\Models\Asuransi;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderanFactory extends Factory
{
    protected $model = Orderan::class;

    public function definition(): array
    {
        $orderDate = $this->faker->dateTimeBetween('-1 months', 'now');

        $total = $this->faker->numberBetween(100000, 3000000);

        $asuransi = \App\Models\Asuransi::inRandomOrder()->first() ?? \App\Models\Asuransi::factory()->create();
        $asuransi_id = $asuransi->id;
        $asuransi_nominal = $asuransi->nominal ?? 0;

        $diskon = $this->faker->randomElement([
            0,
            $this->faker->numberBetween(1000, 100000)
        ]);

        $perlu_dibayar = max($total - $asuransi_nominal - $diskon, 0);

        $customer_paying = $this->faker->numberBetween(
            intval($perlu_dibayar * 0.5),
            intval($perlu_dibayar * 1.5)
        );

        $kurang_bayar = max($perlu_dibayar - $customer_paying, 0);
        $kembalian = max($customer_paying - $perlu_dibayar, 0);

        return [
            'user_id' => \App\Models\User::factory(),
            'cabang_id' => \App\Models\Cabang::factory(),
            'order_date' => $orderDate,
            'complete_date' => $this->faker->optional()->dateTimeBetween($orderDate, 'now'),
            'staff_id' => \App\Models\Staff::factory(),
            'payment_type' => $this->faker->randomElement(['DP', 'pelunasan', 'asuransi']),
            'order_status' => $this->faker->randomElement(['pending', 'complete']),
            'payment_method' => $this->faker->randomElement(['cash', 'card']),
            'payment_status' => $this->faker->randomElement(['unpaid', 'DP', 'paid']),

            'total' => $total,
            'asuransi_id' => $asuransi_id,
            'diskon' => $diskon,
            'perlu_dibayar' => $perlu_dibayar,
            'customer_paying' => $customer_paying,
            'kurang_bayar' => $kurang_bayar,
            'kembalian' => $kembalian,
        ];
    }
}
