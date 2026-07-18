# SiPanitia — Design Guidelines
## Identitas Aplikasi
- Nama: SiPanitia
- Tagline: Sistem Monitoring Kepanitiaan HUT RI
- Konteks pengguna: Panitia HUT RI tingkat RT/RW, sering update dari HP di lapangan/rapat
## Warna
- Primary: Merah maroon #990000 (header, navbar, FAB, tombol utama)
- Background: #FFFFFF
- Surface/Card: #FFFFFF dengan border #E5E7EB dan shadow 0 1px 3px rgba(0,0,0,0.08)
- Divider: #F3F4F6
### Warna Status Badge (KONSISTEN di semua halaman)
| Status | Background | Teks | Penggunaan |
|---|---|---|---|
| Belum Mulai | #E5E7EB (abu-abu) | #6B7280 | Default status awal |
| Proses | #FEF3C7 (kuning muda) | #92400E | Sedang dikerjakan |
| Selesai | #D1FAE5 (hijau muda) | #065F46 | Sudah selesai |
| Tertunda | #FED7AA (oranye muda) | #9A3412 | Ditunda/blocked |
| Overdue | #FEE2E2 (merah muda) | #991B1B | Khusus highlight telat, BUKAN status biasa |
### Warna Progress Card Dashboard
| Kondisi | Warna |
|---|---|
| Progress ≥80% | Hijau #059669 |
| Progress 50-79% | Kuning #D97706 |
| Progress <50% | Merah #DC2626 |
## Tipografi
- Headline: Plus Jakarta Sans (bold/semibold)
- Body/label: Inter (regular/medium)
- Ukuran minimum body text: 14px (readability di HP layar kecil)
- Ukuran minimum label/caption: 12px
## Komponen
- Card: rounded 12px, padding 16px, shadow tipis, border 1px #E5E7EB
- Badge/Chip status: rounded-full (pill), padding 4px 12px, font 12px semibold
- Tombol primary: rounded 12px, bg maroon #990000, teks putih, full-width, min-height 48px
- Tombol secondary: rounded 12px, border maroon, teks maroon, bg putih
- Input field: rounded 8px, border #D1D5DB, focus-border #990000, min-height 48px
- FAB: rounded-full, 56×56px, bg maroon, ikon putih, shadow medium, posisi kanan bawah (bottom-right, 16px margin dari edge, di atas bottom nav)
## Layout Shell
- Top bar: bg maroon #990000, height 56px, logo putih kiri, ikon notifikasi + avatar kanan
- Bottom navigation bar: bg putih, height 64px + safe area, border-top 1px #E5E7EB, 4 tab:
  1. Dashboard (ikon grid/home)
  2. Tugas (ikon checklist)
  3. Logistik (ikon box/inventory)
  4. Profil (ikon person)
- Active tab: ikon + label maroon, inactive: ikon + label abu-abu
- Drawer/hamburger (untuk menu Admin, hanya muncul kalau role Super Admin): buka dari ikon ≡ di top bar, berisi menu tambahan: Manajemen User, Manajemen Divisi, Import Data, Manajemen Event