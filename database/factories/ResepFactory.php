<?php

namespace Database\Factories;

use App\Models\Resep;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResepFactory extends Factory
{
    protected $model = Resep::class;

    public function definition(): array
    {
        // Fungsi pembantu buat bikin kelipatan 0.25 (misal untuk SPH, CYL, ADD, PRISMA)
        $generateStepDecimal = function ($min, $max) {
            $steps = range($min, $max, 0.25);
            return number_format($steps[array_rand($steps)], 2, '.', '');
        };

        return [

            // --- MATA KANAN (OD) ---
            'od_sph' => $generateStepDecimal(-20.00, 20.00),
            'od_cyl' => $generateStepDecimal(-6.00, 0.00),
            'od_axis' => $this->faker->numberBetween(0, 180),
            'od_add' => $generateStepDecimal(0.00, 4.00),
            'od_prisma' => $generateStepDecimal(0.00, 10.00),
            'od_base' => $this->faker->randomElement(['In', 'Out', 'Up', 'Down']),
            'od_va' => '6/6',

            // --- MATA KIRI (OS) ---
            'os_sph' => $generateStepDecimal(-20.00, 20.00),
            'os_cyl' => $generateStepDecimal(-6.00, 0.00),
            'os_axis' => $this->faker->numberBetween(0, 180),
            'os_add' => $generateStepDecimal(0.00, 4.00),
            'os_prisma' => $generateStepDecimal(0.00, 10.00),
            'os_base' => $this->faker->randomElement(['In', 'Out', 'Up', 'Down']),
            'os_va' => '6/6',

            // --- NOTE & TANGGAL ---
            'tanggal_pemeriksaan' => now()->format('Y-m-d'),
            'notes' => 'Sesuai resep standar',

            // --- DATA PRECAL & FRAME (Input angka manual & Dropdown) ---
            'pdr' => $this->faker->randomFloat(1, 25, 40),
            'pdl' => $this->faker->randomFloat(1, 25, 40),
            'pv' => $this->faker->randomFloat(1, 10, 15),
            'frame_a' => $this->faker->randomFloat(1, 40, 60),
            'frame_b' => $this->faker->randomFloat(1, 30, 50),
            'frame_d' => $this->faker->randomFloat(1, 15, 22),
            'frame_diag' => $this->faker->randomFloat(1, 45, 65),
            'frame' => $this->faker->randomElement(['Full Metal', 'Full Plastik', 'Nylon', 'Rimless']),
        ];
    }
}
