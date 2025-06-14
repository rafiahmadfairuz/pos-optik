<?php



namespace Database\Factories;

use App\Models\Staff;
use Illuminate\Database\Eloquent\Factories\Factory;

class StaffFactory extends Factory
{
    protected $model = Staff::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('1234'),
            'role' => 'admin',
        ];
    }

    public function admin()
    {
        return $this->state(fn() => ['role' => 'admin']);
    }

    public function gudang()
    {
        return $this->state(fn() => ['role' => 'gudang']);
    }
}
