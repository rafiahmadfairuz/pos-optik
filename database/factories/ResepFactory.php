<?php

namespace Database\Factories;

use App\Models\Resep;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResepFactory extends Factory
{
    protected $model = Resep::class;

    public function definition(): array
    {
        return [
            'orderan_id' => \App\Models\Orderan::factory(),
            'right_sph_d' => $this->faker->optional()->randomFloat(2, -10, 10),
            'right_cyl_d' => $this->faker->optional()->randomFloat(2, -5, 5),
            'right_axis_d' => $this->faker->optional()->numberBetween(0, 180),
            'right_va_d' => $this->faker->optional()->word(),
            'left_sph_d' => $this->faker->optional()->randomFloat(2, -10, 10),
            'left_cyl_d' => $this->faker->optional()->randomFloat(2, -5, 5),
            'left_axis_d' => $this->faker->optional()->numberBetween(0, 180),
            'left_va_d' => $this->faker->optional()->word(),
            'add_right' => $this->faker->optional()->randomFloat(2, 0, 3),
            'add_left' => $this->faker->optional()->randomFloat(2, 0, 3),
            'pd_right' => $this->faker->optional()->randomFloat(2, 50, 70),
            'pd_left' => $this->faker->optional()->randomFloat(2, 50, 70),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
