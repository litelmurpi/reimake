<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Divisi;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@sipanitia.local'],
            ['name' => 'Super Admin', 'password' => bcrypt('password')]
        );
        $superAdmin->assignRole('super_admin');

        $bphUser = User::firstOrCreate(
            ['email' => 'bph@sipanitia.local'],
            ['name' => 'Ketua BPH', 'password' => bcrypt('password')]
        );
        $bphUser->assignRole('bph');
        $bphDivisi = Divisi::where('nama_divisi', 'BPH')->first();
        if ($bphDivisi && !$bphUser->belongsToDivisi($bphDivisi)) {
            $bphUser->divisis()->attach($bphDivisi->id, ['is_koordinator' => true]);
        }
    }
}
