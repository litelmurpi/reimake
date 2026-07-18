<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Divisi;

class DivisiSeeder extends Seeder
{
    public function run(): void
    {
        $divisis = [
            ['nama_divisi' => 'BPH', 'fokus_utama' => 'Koordinasi umum, administrasi, keuangan'],
            ['nama_divisi' => 'Sie Humas', 'fokus_utama' => 'Sosialisasi, komunikasi warga'],
            ['nama_divisi' => 'Sie Dekdok', 'fokus_utama' => 'Dekorasi & dokumentasi'],
            ['nama_divisi' => 'Sie Acara', 'fokus_utama' => 'Perencanaan & pelaksanaan lomba/kegiatan'],
            ['nama_divisi' => 'Sie Perkap', 'fokus_utama' => 'Logistik, pinjam/beli barang'],
            ['nama_divisi' => 'Sie Konsumsi', 'fokus_utama' => 'Kebutuhan konsumsi per acara'],
        ];

        foreach ($divisis as $divisi) {
            Divisi::firstOrCreate(['nama_divisi' => $divisi['nama_divisi']], $divisi);
        }
    }
}
