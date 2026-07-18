<?php

namespace Database\Factories;

use App\Models\AdminTask;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AdminTask>
 */
class AdminTaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_tugas' => $this->faker->sentence(3),
            'target_tanggal' => $this->faker->date(),
            'status' => 'Belum Mulai',
            'catatan' => $this->faker->paragraph(),
        ];
    }
}
