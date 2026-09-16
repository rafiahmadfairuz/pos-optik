<?php

namespace Database\Factories;

use App\Models\Orderan;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderanFactory extends Factory
{
    protected $model = Orderan::class;

    public function definition(): array
    {
        $orderDate = $this->faker->dateTimeBetween('-1 months', 'now');
        $total = $this->faker->numberBetween(100000, 3000000);
        $diskon = $this->faker->randomElement([0, 50000, 100000]);

        $paymentStatus = $this->faker->randomElement(['unpaid', 'DP', 'paid']);

        // Logika finansial yang konsisten berdasarkan status pembayaran
        if ($paymentStatus === 'paid') {
            $perluDibayar = max($total - $diskon, 0);
            $customerPaying = $perluDibayar + $this->faker->numberBetween(0, 50000); // Bisa pas atau ada kembalian
            $kurangBayar = 0;
            $kembalian = $customerPaying - $perluDibayar;
            $paymentType = 'pelunasan';
            $orderStatus = 'complete';
        } elseif ($paymentStatus === 'DP') {
            $perluDibayar = max($total - $diskon, 0);
            $customerPaying = intval($perluDibayar * 0.5); // Bayar setengahnya
            $kurangBayar = $perluDibayar - $customerPaying;
            $kembalian = 0;
            $paymentType = 'DP';
            $orderStatus = 'pending';
        } else { // unpaid
            $perluDibayar = max($total - $diskon, 0);
            $customerPaying = 0;
            $kurangBayar = $perluDibayar;
            $kembalian = 0;
            $paymentType = 'DP';
            $orderStatus = 'pending';
        }

        return [
            'order_date' => $orderDate,
            'complete_date' => $orderStatus === 'complete' ? $this->faker->dateTimeBetween($orderDate, 'now') : null,
            'payment_type' => $paymentType,
            'order_status' => $orderStatus,
            'payment_method' => $this->faker->randomElement(['cash', 'card']),
            'payment_status' => $paymentStatus,
            'total' => $total,
            'diskon' => $diskon,
            'perlu_dibayar' => $perluDibayar,
            'customer_paying' => $customerPaying,
            'kurang_bayar' => $kurangBayar,
            'kembalian' => $kembalian,
        ];
    }
}
