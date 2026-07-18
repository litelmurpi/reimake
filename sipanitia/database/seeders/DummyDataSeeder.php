<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdminTask;
use App\Models\Divisi;
use App\Models\User;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $bph = Divisi::where('nama_divisi', 'BPH')->first();
        $admin = User::first();
        
        if ($bph && $admin) {
            AdminTask::create([
                'divisi_id' => $bph->id,
                'nama_tugas' => 'Buat Proposal Kegiatan',
                'target_tanggal' => '2026-07-20',
                'pj_user_id' => $admin->id,
                'status' => 'Belum Mulai',
            ]);
        }
    }
}
