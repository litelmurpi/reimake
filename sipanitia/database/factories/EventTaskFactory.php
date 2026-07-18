<?php

namespace Database\Factories;

use App\Models\EventTask;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EventTask>
 */
class EventTaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sub_event' => $this->faker->sentence(2),
            'kebutuhan_aspek' => $this->faker->word(),
            'target_tanggal' => $this->faker->date(),
            'detail_deskripsi' => $this->faker->paragraph(),
            'status' => 'Belum Mulai',
            'catatan_plan_b' => $this->faker->paragraph(),
        ];
    }
}
