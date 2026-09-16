<?php

namespace Database\Factories;

use App\Models\Cabang;
use App\Models\Transfer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transfer>
 */
class TransferFactory extends Factory
{
    protected $model = Transfer::class;

    public function definition(): array
    {
        $cabangIds = Cabang::pluck('id')->toArray();

        if (count($cabangIds) >= 2) {
            shuffle($cabangIds);
            $from = $cabangIds[0];
            $to = $cabangIds[1];
        } else {
            $from = 0;
            $to = $cabangIds[0] ?? 1;
        }

        return [
            'from_cabang_id' => $from,
            'to_cabang_id'   => $to,
            'tanggal'        => now(),
            'kode'           => 'TF-' . date('Ymd') . '-' . $this->faker->unique()->numberBetween(1000, 9999),
            'status'         => $this->faker->randomElement(['completed', 'returned', 'transferred_out']),
        ];
    }
}
