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
        $paying = $this->faker->randomFloat(2, 100000, 2000000);
        $due = $this->faker->randomFloat(2, 100000, 2000000);

        return [
            'user_id' => \App\Models\User::factory(),
            'cabang_id' => \App\Models\Cabang::factory(),
            'order_date' => $orderDate,
            'complete_date' => $this->faker->optional()->dateTimeBetween($orderDate, 'now'),
            'staff_id' => \App\Models\Staff::factory(),
            'payment_type' => $this->faker->randomElement(['DP', 'pelunasan', 'asuransi']),
            'order_status' => $this->faker->randomElement(['pending', 'complete']),
            'payment_method' => $this->faker->randomElement(['cash', 'card']),
            'payment_status' => $this->faker->randomElement(['unpaid', 'paid']),
            'customer_paying' => $paying,
            'perlu_dibayar' => $due,
            'kembalian' => ($paying - $due) > 0 ? $paying - $due : null,
            'asuransi_id' => $this->faker->optional()->randomDigitNotNull(),
            'total' => $due,
        ];
    }
}
