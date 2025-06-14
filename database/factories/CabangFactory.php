<?php

namespace Database\Factories;

use App\Models\Cabang;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CabangFactory extends Factory
{
    protected $model = Cabang::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->company();

        return [
            'nama' => $name,
            'slug' => Str::slug($name),
            'alamat' => $this->faker->address(), 
        ];
    }
}
