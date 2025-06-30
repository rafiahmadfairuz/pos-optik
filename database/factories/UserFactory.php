<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'alamat' => $this->faker->address(),
            'umur' => $this->faker->numberBetween(4, 60),
            'gender' => $this->faker->randomElement(['male', 'female', 'other']),
            'cabang_id' => \App\Models\Cabang::factory(),
        ];
    }
}
