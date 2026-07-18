<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            ['nama_event' => 'Lomba Anak', 'tanggal' => '2026-08-15'],
            ['nama_event' => 'Lomba Ibu-Ibu', 'tanggal' => '2026-08-15'],
            ['nama_event' => 'Outbound', 'tanggal' => '2026-08-16'],
            ['nama_event' => 'Tirakatan', 'tanggal' => '2026-08-16'],
            ['nama_event' => 'Jalan Sehat', 'tanggal' => '2026-08-17'],
        ];

        foreach ($events as $event) {
            Event::firstOrCreate(['nama_event' => $event['nama_event']], $event);
        }
    }
}
