<?php
namespace Database\Factories;
use App\Models\LensaKhusus;
use Illuminate\Database\Eloquent\Factories\Factory;

class LensaKhususFactory extends Factory
{
    protected $model = LensaKhusus::class;

    public function definition(): array
    {
        return [
            'merk' => ucfirst($this->faker->word()),
            'desain' => ucfirst($this->faker->word()),
            'tipe' => ucfirst($this->faker->word()),
            'sph' => $this->faker->randomFloat(2, -10, 10),
            'cyl' => $this->faker->randomFloat(2, -5, 5),
            'add' => $this->faker->randomFloat(2, 0, 3),
            'estimasi_selesai_hari' => rand(1, 7),
            'status_pesanan' => $this->faker->randomElement(['menunggu', 'proses', 'selesai']),
            'cabang_id' => \App\Models\Cabang::inRandomOrder()->first()->id,
        ];
    }
}
