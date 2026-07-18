<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_event' => $this->faker->sentence(2),
            'tanggal' => $this->faker->date(),
            'deskripsi' => $this->faker->paragraph(),
            'status' => 'Aktif',
        ];
    }
}
