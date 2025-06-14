<?php

namespace Database\Factories;

use App\Models\Asuransi;
use Illuminate\Database\Eloquent\Factories\Factory;

class AsuransiFactory extends Factory
{
    protected $model = Asuransi::class;

    public function definition(): array
    {
        return [
            'nama' => $this->faker->company . ' Asuransi',
            'nominal' => $this->faker->numberBetween(1000, 20000),
            'cabang_id' => \App\Models\Cabang::factory(),
        ];
    }
}
