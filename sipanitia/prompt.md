# Prompt Stitch — SiPanitia (Mobile-First)

Dokumen ini berisi kumpulan prompt siap-pakai untuk digenerate di **Google Stitch**, berdasarkan `PRD.md` (v1.3). Semua prompt ditulis untuk **mobile-first** (viewport 375-414px, portrait).

> **Versi:** 1.1 (post-audit, penyesuaian Stitch API)

---

## Cara Pakai di Stitch

### Langkah 1 — Buat Project
Buat project baru di Stitch bernama "SiPanitia".

### Langkah 2 — Setup Design System
Jalankan **Prompt 0A** untuk membuat design system via Stitch API (`create_design_system`) dengan parameter yang sudah dipetakan. Lalu upload **Prompt 0B** sebagai Design MD (`upload_design_md`) untuk instruksi tambahan yang tidak bisa dicover parameter API.

### Langkah 3 — Generate Screen Satu-Satu
- Generate tiap prompt di bawah dengan `generate_screen_from_text`, set `deviceType: "MOBILE"`.
- Setiap prompt sudah **self-contained** (ada konteks desain di awal), jadi bisa digenerate terpisah/tidak berurutan.
- Pastikan `designSystem` diisi dengan ID dari Langkah 2 agar warna & font konsisten.

### Langkah 4 — Iterasi
- Kalau hasil kurang sesuai, gunakan `edit_screens` dengan prompt perbaikan spesifik.
- Gunakan `generate_variants` (creative range: `REFINE`) untuk eksplorasi layout alternatif.
- Tambahkan "match the previous screen's header style and color palette" jika inkonsistensi muncul.

### Aturan Mobile-First Global
Semua prompt di bawah mengikuti aturan ini (tidak perlu diulang di tiap prompt, tapi sudah disertakan untuk kejelasan saat copy-paste):
- **Touch targets minimum 44×44px** — semua tombol, link, checkbox, toggle harus cukup besar untuk jari.
- **Safe area** — konten tidak boleh terpotong notch/status bar (padding-top aman) dan home indicator iOS (padding-bottom).
- **Scroll direction** — halaman utama scroll vertikal, filter chips scroll horizontal.
- **Thumb zone** — aksi utama (FAB, tombol Simpan) ditempatkan di area bawah layar yang mudah dijangkau ibu jari.
- **Data dummy realistis** — JANGAN pakai "Lorem ipsum" atau placeholder generik. Gunakan contoh data nyata berbahasa Indonesia sesuai konteks HUT RI.

---

## Prompt 0A — Design System (Stitch API Parameters)

Gunakan `create_design_system` dengan parameter berikut:

```json
{
  "projectId": "<PROJECT_ID>",
  "designSystem": {
    "displayName": "SiPanitia Mobile Design System",
    "theme": {
      "customColor": "#990000",
      "colorMode": "LIGHT",
      "colorVariant": "TONAL_SPOT",
      "headlineFont": "PLUS_JAKARTA_SANS",
      "bodyFont": "INTER",
      "labelFont": "INTER",
      "roundness": "ROUND_TWELVE",
      "overridePrimaryColor": "#990000",
      "overrideSecondaryColor": "#F5F5F5",
      "overrideNeutralColor": "#FAFAFA"
    }
  }
}
```

> **Catatan font:** PRD menyebutkan Inter/Poppins. Stitch API mendukung `INTER` dan `PLUS_JAKARTA_SANS` (karakter mirip Poppins, lebih modern). Poppins sendiri tidak tersedia di enum Stitch.

---

## Prompt 0B — Design MD (Upload via `upload_design_md`)

Encode konten markdown di bawah ini ke base64, lalu upload via `upload_design_md`.

```markdown
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
```

---

## Prompt 0C — App Shell Screen

```
Buatkan mobile screen shell untuk aplikasi "SiPanitia" — sistem monitoring kepanitiaan HUT RI RT/RW. Mobile viewport 375px wide.

Komponen yang harus ditampilkan:
1. STATUS BAR: hitam, jam + ikon sinyal/baterai (standar iOS/Android)
2. TOP BAR: background merah maroon (#990000), height 56px. Di kiri: logo teks "SiPanitia" warna putih bold. Di kanan: ikon lonceng notifikasi putih (dengan badge angka merah "3") dan avatar profil bulat kecil (32px)
3. CONTENT AREA: background putih, isi placeholder teks "Konten halaman di sini" di tengah, warna abu-abu
4. BOTTOM NAVIGATION BAR: background putih, border-top abu-abu tipis, height 64px. 4 tab masing-masing dengan ikon dan label di bawahnya:
   - "Dashboard" (ikon grid) — AKTIF, warna maroon
   - "Tugas" (ikon checklist) — inactive, warna abu-abu
   - "Logistik" (ikon box) — inactive, warna abu-abu
   - "Profil" (ikon person) — inactive, warna abu-abu
5. HOME INDICATOR: bar kecil hitam di paling bawah (safe area iOS)

Desain bersih, modern, sans-serif. Ini adalah referensi visual shell yang akan dipakai di semua halaman berikutnya.
```

---

## A. Autentikasi

### Prompt A1 — Login

```
Aplikasi "SiPanitia", mobile viewport 375px, tema warna merah maroon (#990000) + putih, font Plus Jakarta Sans/Inter, rounded 12px.

Buatkan HALAMAN LOGIN mobile:
- Bagian atas (40% layar): background gradien maroon-to-dark (#990000 → #660000), logo teks "SiPanitia" besar warna putih bold, tagline "Sistem Monitoring Kepanitiaan HUT RI" putih semi-transparan di bawahnya
- Bagian bawah (60% layar): card putih dengan rounded-top-24px overlap sedikit ke area maroon
  - Input Email/Username: placeholder "Email atau username", ikon amplop di kiri, height 48px
  - Input Password: placeholder "Password", ikon gembok di kiri, ikon show/hide di kanan, height 48px
  - Link "Lupa Password?" rata kanan, font 13px, warna maroon
  - Tombol "Masuk" full-width height 48px bg maroon, teks putih bold, rounded 12px, margin-top 16px
  - Teks kecil 12px abu-abu di bawah: "Belum punya akun? Hubungi Super Admin"
- TIDAK ada bottom navigation bar di halaman ini (belum login)
- Desain premium, banyak whitespace, cocok untuk first impression
```

### Prompt A2 — Lupa Password

```
Aplikasi "SiPanitia", mobile viewport 375px, tema warna merah maroon (#990000) + putih.

Buatkan HALAMAN LUPA PASSWORD mobile, tampilkan 2 STATE dalam 1 screen (atas-bawah, bukan overlay):

STATE 1 — Form:
- Top bar putih: tombol back (←) di kiri, judul "Lupa Password" di tengah
- Ilustrasi/ikon kunci besar (outline style, warna maroon) di tengah, 80×80px
- Teks: "Masukkan email yang terdaftar, kami akan kirim link reset password" — font 14px, warna abu-abu, text-align center
- Input Email, height 48px
- Tombol "Kirim Link Reset" full-width maroon

STATE 2 — Sukses (ditampilkan DI BAWAH state 1, dengan separator "— setelah berhasil —"):
- Card dengan background hijau muda (#D1FAE5), ikon ✓ hijau, rounded 12px
- Teks: "Link reset sudah dikirim ke email kamu. Cek inbox (atau spam)."
- Tombol "Kembali ke Login" outline maroon
```

### Prompt A3 — Reset Password

```
Aplikasi "SiPanitia", mobile viewport 375px, tema warna merah maroon (#990000) + putih.

Buatkan HALAMAN RESET PASSWORD mobile:
- Top bar putih: judul "Buat Password Baru" di tengah
- Ikon shield/lock besar (outline, maroon) di tengah, 64×64px
- Input "Password Baru" height 48px, ikon show/hide
- Helper text 12px abu-abu: "Minimum 8 karakter"
- Input "Konfirmasi Password Baru" height 48px, ikon show/hide
- Helper text 12px: akan berwarna merah "Password tidak cocok" kalau mismatch, hijau "Password cocok" kalau match
- Tombol "Simpan Password Baru" full-width maroon, height 48px
- Tidak ada bottom nav (flow dari email link, bukan dari dalam app)
```

---

## B. Dashboard

### Prompt B1 — Dashboard (Home)

```
Aplikasi "SiPanitia", mobile viewport 375px, tema warna merah maroon (#990000) + putih. Tab "Dashboard" di bottom nav aktif (warna maroon).

Buatkan HALAMAN DASHBOARD mobile, scrollable, urutan atas ke bawah:

1. TOP BAR: bg maroon, "SiPanitia" putih di kiri, ikon lonceng (badge "5") + avatar di kanan
2. GREETING SECTION: padding 16px, "Halo, Pak Ahmad 👋" bold 18px, "Ketua Panitia" badge kecil abu-abu di bawahnya
3. OVERALL PROGRESS: card putih, shadow, di dalamnya circular progress indicator besar (diameter ~100px) menampilkan "67%" di tengah lingkaran, warna kuning (#D97706 karena 50-79%), teks "Progress Keseluruhan Panitia" di bawah lingkaran
4. PROGRESS PER DIVISI: grid 2×2 card kecil, masing-masing berisi:
   - "BPH" — progress bar 80% hijau, "8/10 selesai"
   - "Sie Acara" — progress bar 55% kuning, "11/20 selesai"
   - "Sie Perkap" — progress bar 40% merah, "6/15 selesai"
   - "Sie Konsumsi" — progress bar 75% kuning, "9/12 selesai"
5. WIDGET TOTAL ANGGARAN: card putih, ikon uang, "Total Estimasi Anggaran" label, "Rp 5.800.000" besar bold, breakdown kecil "Perkap: Rp 3.200.000 · Konsumsi: Rp 2.600.000"
6. WIDGET TUGAS TERLAMBAT: judul "⚠️ Tugas Terlambat" dengan badge merah "4", lalu 3 card kecil masing-masing:
   - Card 1: "Sebar Undangan RT" — badge merah "Telat 5 hari" — "BPH · Pak Budi"
   - Card 2: "Booking Sound System" — badge merah "Telat 3 hari" — "Sie Perkap · Mas Andi"
   - Card 3: "Pesan Snack Outbound" — badge merah "Telat 1 hari" — "Sie Konsumsi · Bu Sari"
   - Tombol kecil "Lihat Semua →" di bawah
7. PANDUAN: accordion collapsed "📖 Panduan Penggunaan"
8. BOTTOM NAV: 4 tab, Dashboard aktif maroon

Gunakan data dummy realistis seperti di atas, BUKAN lorem ipsum.
```

---

## C. Modul Checklist Tugas (BPH/Humas/Dekdok & Sie Acara)

### Prompt C1 — List Tugas: BPH/Humas/Dekdok

```
Aplikasi "SiPanitia", mobile viewport 375px, tema merah maroon (#990000) + putih. Card view, bukan tabel.

Buatkan HALAMAN LIST TUGAS mobile — Modul "BPH, Humas & Dekdok":

- TOP BAR putih: tombol back (←), judul "Checklist BPH/Humas/Dekdok"
- SEARCH BAR: input dengan ikon 🔍, placeholder "Cari tugas...", height 40px
- FILTER CHIPS: horizontal scroll, pill-shaped: "Semua" (aktif, bg maroon, teks putih), "Belum Mulai", "Proses", "Selesai", "Tertunda" (inactive, bg abu-abu muda)
- LIST CARD — tampilkan 4 contoh card dengan data dummy realistis:

  Card 1: ✅ normal
  - Checkbox □ di kiri | "Buat Proposal Kegiatan" bold | badge hijau "Selesai"
  - 📅 12 Jul 2026 | 👤 Pak Budi (avatar kecil)
  - Footer abu-abu kecil: "Diubah oleh Pak Ahmad · 2 jam lalu"

  Card 2: ⚠️ overdue (LEFT ACCENT MERAH 4px)
  - Checkbox □ | "Sebar Undangan ke RT 01-05" bold | badge kuning "Proses"
  - 📅 15 Jul 2026 (teks merah "Telat 3 hari") | 👤 Pak Budi
  - Footer: "Diubah oleh Pak Budi · kemarin"

  Card 3: normal
  - Checkbox □ | "Koordinasi Sponsor" bold | badge abu "Belum Mulai"
  - 📅 25 Jul 2026 | 👤 Bu Ani
  - Footer: "Belum ada perubahan"

  Card 4: normal
  - Checkbox □ | "Bikin Video Tutorial Outbound" bold | badge oranye "Tertunda"
  - 📅 20 Jul 2026 | 👤 Mas Deni
  - Footer: "Diubah oleh Mas Deni · 3 hari lalu"

- STATE MULTI-SELECT: tunjukkan juga versi di mana 2 checkbox tercentang, muncul BOTTOM ACTION BAR overlay: "2 dipilih" + tombol maroon "Ubah Status ▼"
- FAB: bulat maroon 56px, ikon "+", posisi kanan bawah di atas bottom nav
- BOTTOM NAV: tab "Tugas" aktif
```

### Prompt C1-empty — Empty State: List Tugas Kosong

```
Aplikasi "SiPanitia", mobile viewport 375px, maroon + putih.

Buatkan EMPTY STATE untuk halaman list tugas saat belum ada data:
- Top bar + search + filter chip sama seperti halaman list biasa
- Area tengah: ilustrasi sederhana (outline style, warna maroon muda) clipboard kosong atau checklist kosong, ukuran ~120×120px
- Teks "Belum ada tugas" bold 16px hitam, di bawahnya "Tap tombol + untuk menambah tugas pertama" font 14px abu-abu
- FAB "+" tetap ada di kanan bawah
- Bottom nav tetap terlihat
```

### Prompt C2 — Form Tambah/Edit Tugas: BPH/Humas/Dekdok

```
Aplikasi "SiPanitia", mobile viewport 375px, maroon + putih. Form sebagai full-page modal (bukan bottom sheet — terlalu banyak field untuk bottom sheet).

Buatkan FORM TAMBAH TUGAS BARU mobile — Modul "BPH, Humas & Dekdok":
- HEADER: bg putih, "Tambah Tugas Baru" bold di tengah, tombol ✕ di kanan
- FORM FIELDS (scrollable, padding 16px, gap 16px antar field):
  1. Dropdown "Divisi" — placeholder "Pilih divisi", opsi: BPH, Sie Humas, Sie Dekdok
  2. Input "Nama Tugas" — placeholder "Contoh: Buat Proposal Kegiatan"
  3. Date picker "Target Tanggal" — format DD/MM/YYYY, ikon kalender di kanan
  4. Searchable select "Penanggung Jawab" — placeholder "Cari nama PJ...", menampilkan avatar + nama dalam dropdown
  5. Dropdown "Status" — setiap opsi ada preview badge warna di kiri: ● Belum Mulai (abu), ● Proses (kuning), ● Selesai (hijau), ● Tertunda (oranye)
  6. Textarea "Catatan" — placeholder "Tambahkan catatan jika perlu", auto-expand, max 4 baris awal
- BOTTOM STICKY (bg putih, shadow-up, padding 16px):
  - Tombol "Batal" outline maroon, full-width
  - Tombol "Simpan" bg maroon teks putih, full-width, height 48px
  - 12px gap antar tombol
- Semua input field height 48px, rounded 8px, focus state border maroon
```

### Prompt C3 — List Tugas: Sie Acara

```
Aplikasi "SiPanitia", mobile viewport 375px, maroon + putih. Card view.

Buatkan HALAMAN LIST TUGAS mobile — Modul "Sie Acara":
- TOP BAR putih: back + "Checklist Sie Acara"
- FILTER CHIPS row 1 (sub-event, horizontal scroll): "Semua" (aktif), "Lomba Anak", "Lomba Ibu-Ibu", "Outbound", "Tirakatan", "Jalan Sehat"
- FILTER row 2: dropdown kecil "Status: Semua ▼" di kiri
- LIST CARD — 4 contoh card:

  Card 1:
  - Label kecil maroon "OUTBOUND" | badge kuning "Proses"
  - "Persiapan Clue & Penandaan Rute" bold
  - 📅 12 Agt 2026 | 👤 Mas Andi | 🎤 MC: - | left-accent MERAH (overdue)

  Card 2:
  - Label "LOMBA ANAK" | badge abu "Belum Mulai"
  - "Siapkan Peralatan Bakiak & Egrang" bold
  - 📅 10 Agt 2026 | 👤 Bu Siti | 🔧 Perkap: 2 set bakiak, 2 set egrang

  Card 3:
  - Label "TIRAKATAN" | badge hijau "Selesai"
  - "Casting Pemain Ketoprak" bold
  - 📅 23 Jul 2026 | 👤 Pak Joko | 🎤 Sutradara: Mas Bejo

  Card 4:
  - Label "JALAN SEHAT" | badge oranye "Tertunda"
  - "Koordinasi Rute dengan Polsek" bold
  - 📅 1 Agt 2026 | 👤 Pak RT

- FAB "+" maroon
- Bottom nav: "Tugas" aktif
```

### Prompt C4 — Form Tambah/Edit Tugas: Sie Acara

```
Aplikasi "SiPanitia", mobile viewport 375px, maroon + putih.

Buatkan FORM TAMBAH TUGAS ACARA mobile — form ini lebih panjang dari modul BPH, jadi gunakan SECTION GROUPING:

HEADER: "Tambah Tugas Acara" + tombol ✕

SECTION 1 — "Info Dasar" (label section abu-abu bold uppercase 12px):
- Dropdown Sub-Event: Lomba Anak / Lomba Ibu-Ibu / Outbound / Tirakatan / Jalan Sehat
- Input "Kebutuhan/Aspek Acara": placeholder "Contoh: Persiapan Clue Outbound"
- Date picker "Target Tanggal"
- Select PJ (searchable, avatar + nama)
- Dropdown Status (dengan preview badge warna)

SECTION 2 — "Detail Pelaksanaan":
- Input "MC / Pengisi Acara": placeholder "Contoh: Mas Bejo"
- Input "Perlengkapan Utama": placeholder "Contoh: 2 set bakiak, 5 ember"
- Textarea "Detail Aturan/Rute/Deskripsi" (auto-expand)

SECTION 3 — "Contingency":
- Textarea "Catatan Plan B / Resiko": placeholder "Contoh: Jika hujan, pindah ke pendopo"

BOTTOM STICKY: Tombol Batal (outline) + Simpan (maroon solid), height 48px

Scroll indicator kecil di sisi kanan untuk menunjukkan form bisa discroll.
```

### Prompt C5 — Detail Tugas (BPH/Acara)

```
Aplikasi "SiPanitia", mobile viewport 375px, maroon + putih.

Buatkan HALAMAN DETAIL TUGAS mobile — berlaku untuk BPH/Humas/Dekdok maupun Sie Acara. Isi dengan data dummy realistis:

- TOP BAR putih: ← back | "Sebar Undangan ke RT" (ellipsis kalau kepanjangan) | ikon ✏️ edit di kanan
- BADGE STATUS BESAR: di bawah top bar, full-width, bg kuning muda, teks "Proses" + ikon spinner, rounded 8px
- OVERDUE ALERT: card kecil bg merah muda (#FEE2E2), ikon ⚠️, "Terlambat 3 hari dari target (15 Jul 2026)", hanya muncul kalau overdue

- INFO SECTION (list vertikal, divider antar item):
  - Divisi: "BPH" (badge)
  - Target Tanggal: "15 Juli 2026" (merah karena overdue)
  - Penanggung Jawab: avatar + "Pak Budi" + badge "BPH"
  - Catatan: "Undangan fisik ke RT 01-05, undangan digital ke RT 06-10 via WA grup. Perlu cap Ketua Panitia di undangan fisik."

- SECTION "Riwayat Perubahan" (timeline vertikal, garis abu di kiri, dot warna per event):
  - 🟡 "Pak Budi mengubah status: Belum Mulai → Proses" — 14 Jul 2026, 10:30
  - 🟢 "Pak Ahmad menambahkan catatan" — 13 Jul 2026, 16:15
  - ⚪ "Pak Ahmad membuat tugas ini" — 10 Jul 2026, 09:00

- BOTTOM STICKY: tombol full-width "Ubah Status" bg maroon, tap membuka dropdown kecil (bottom sheet mini) dengan 4 pilihan status + badge warna
- Tidak ada bottom nav (halaman detail, bukan tab utama)
```

---

## D. Modul Inventaris (Sie Perkap)

### Prompt D1 — List Inventaris

```
Aplikasi "SiPanitia", mobile viewport 375px, maroon + putih. Tabel 12 kolom di desktop → CARD VIEW di mobile.

Buatkan HALAMAN LIST INVENTARIS mobile — Modul "Sie Perkap":

- TOP BAR putih: back + "Inventaris & Logistik"
- FILTER TAB: 4 tab horizontal full-width (bukan chip): "Semua" (aktif, underline maroon), "Pinjam", "Beli", "Inv. Sendiri"
- FILTER CHIPS row 2 (opsional, status): "Belum Kembali", "Sudah Kembali", "Habis Pakai", "Tersedia"

- LIST CARD — 4 contoh:

  Card 1: overdue (LEFT ACCENT MERAH 4px)
  - "Sound System 1 Set" bold | badge biru "Pinjam"
  - 📅 Deadline: 14 Jul 2026 (merah "Telat 4 hari") | 💰 Rp 0 (pinjam)
  - 👤 PJ: Mas Andi | 🏠 Dari: Pak RT 03
  - 📍 Posisi: "Di gudang balai desa" teks abu kecil
  - Badge status: merah "Belum Kembali"

  Card 2: normal
  - "Tali Tampar 50m" bold | badge hijau "Beli"
  - 📅 Beli: 10 Jul 2026 | 💰 Rp 75.000
  - 👤 PJ: Bu Ani | 🏪 Toko: TB Makmur
  - Badge: hijau "Habis Pakai"

  Card 3:
  - "Tenda Terpal 4x6m" bold | badge biru "Pinjam"
  - 📅 Deadline: 15 Agt 2026 | 💰 Rp 0
  - 👤 PJ: Pak Joko | 🏠 Dari: Kelurahan
  - Badge: abu "Belum Kembali"

  Card 4:
  - "HT 4 Unit" bold | badge abu "Inv. Sendiri"
  - 💰 Rp 0 (milik panitia)
  - Badge: hijau tua "Tersedia"

- FOOTER STICKY: card maroon gelap, "Total Estimasi Biaya: Rp 3.200.000" teks putih bold
- FAB "+" maroon
- Bottom nav: "Logistik" aktif
```

### Prompt D2 — Form Tambah/Edit Inventaris

```
Aplikasi "SiPanitia", mobile viewport 375px, maroon + putih.

Buatkan FORM TAMBAH BARANG INVENTARIS mobile — modul "Sie Perkap":

HEADER: "Tambah Barang" + ✕

FORM (scrollable, section grouping):

SECTION 1 — "Info Barang":
- Input "Nama Barang": placeholder "Contoh: Sound System 1 Set"
- Input "Divisi Peminta": placeholder "Contoh: Sie Acara"
- Dropdown "Sistem Pengadaan": Pinjam / Beli / Inventaris Milik Sendiri
  → KONDISI DINAMIS: kalau "Pinjam" dipilih, tampilkan field "Pihak yang Dipinjami" dan "Tanggal Kembali". Kalau "Beli", tampilkan "Nama Toko/Vendor". Kalau "Inventaris Sendiri", sembunyikan keduanya.

SECTION 2 — "Jadwal & Biaya":
- Date picker "Tanggal Pinjam/Beli"
- Date picker "Deadline Kumpul"
- Date picker "Tanggal Kembali" (hanya muncul kalau Pinjam)
- Input "Estimasi Biaya": prefix "Rp", format otomatis ribuan (placeholder "0")

SECTION 3 — "Status & Posisi":
- Select PJ Peminjam (searchable)
- Input "Pihak Dipinjami/Toko" (hanya kalau Pinjam/Beli)
- Input "Posisi Barang Terkini": placeholder "Contoh: Di gudang balai desa"
- Dropdown "Status": Tersedia / Belum Kembali / Sudah Kembali / Habis Pakai (dengan warna badge)
- Textarea Catatan

BOTTOM STICKY: Batal + Simpan (maroon, 48px)
```

### Prompt D3 — Detail Inventaris *(BARU)*

```
Aplikasi "SiPanitia", mobile viewport 375px, maroon + putih.

Buatkan HALAMAN DETAIL INVENTARIS mobile — data dummy realistis:

- TOP BAR: back + "Sound System 1 Set" + ikon ✏️
- OVERDUE ALERT: card merah muda, "Deadline kumpul terlewat 4 hari (14 Jul 2026)"
- BADGE STATUS: full-width, bg merah muda, "Belum Kembali"

- INFO CARDS (grouped):
  Group 1 — "Info Barang":
  - Sistem Pengadaan: badge "Pinjam"
  - Divisi Peminta: "Sie Acara"
  - Pihak Dipinjami: "Pak RT 03"

  Group 2 — "Jadwal":
  - Tanggal Pinjam: 10 Jul 2026
  - Deadline Kumpul: 14 Jul 2026 (merah, overdue)
  - Tanggal Kembali: - (belum diisi)

  Group 3 — "Biaya & Posisi":
  - Estimasi Biaya: Rp 0 (pinjam, gratis)
  - PJ: avatar + "Mas Andi"
  - Posisi Terkini: "Di gudang balai desa"

  Catatan: "Speaker aktif 2 unit + mixer + 2 mic wireless. Kabel extension perlu dibawa sendiri."

- RIWAYAT PERUBAHAN (timeline): 2-3 entry
- BOTTOM STICKY: tombol "Ubah Status" maroon
```

---

## E. Modul Konsumsi (Sie Konsumsi)

### Prompt E1 — List Konsumsi

```
Aplikasi "SiPanitia", mobile viewport 375px, maroon + putih.

Buatkan HALAMAN LIST KONSUMSI mobile — Modul "Sie Konsumsi":

- TOP BAR putih: back + "Kebutuhan Konsumsi"
- FILTER CHIPS: "Semua" (aktif), "Belum Dipesan", "Dipesan", "Siap Hari H"

- LIST CARD — 4 contoh:

  Card 1:
  - Label kecil "OUTBOUND" di atas
  - "Snack Jajanan Pasar" bold
  - 100 porsi × Rp 5.000 = **Rp 500.000** (bold, warna maroon, ikon 🧮 kecil)
  - 👤 PJ: Bu Sari | badge hijau "Siap Hari H"

  Card 2:
  - Label "OUTBOUND"
  - "Nasi Liwet Bungkus Daun" bold
  - 130 porsi × Rp 8.000 = **Rp 1.040.000**
  - 👤 PJ: Bu Ani | badge kuning "Dipesan"

  Card 3:
  - Label "TIRAKATAN"
  - "Tumpeng & Lauk Bancakan" bold
  - 200 porsi × Rp 5.000 = **Rp 1.000.000**
  - 👤 PJ: Bu Sari | badge abu "Belum Dipesan"

  Card 4:
  - Label "TIRAKATAN"
  - "Wedang Jahe & Teh Poci" bold
  - 250 porsi × Rp 2.000 = **Rp 500.000**
  - 👤 PJ: Mbak Rina | badge kuning "Dipesan"

- FOOTER STICKY: card maroon gelap, "Total Estimasi Anggaran: Rp 2.600.000" teks putih bold, ikon 💰
- FAB "+"
- Bottom nav: "Logistik" aktif
```

### Prompt E2 — Form Tambah/Edit Konsumsi

```
Aplikasi "SiPanitia", mobile viewport 375px, maroon + putih.

Buatkan FORM TAMBAH KONSUMSI mobile:

HEADER: "Tambah Item Konsumsi" + ✕

FORM:
- Dropdown "Event/Kegiatan": Outbound / Tirakatan / Lomba Anak / Lomba Ibu-Ibu / Jalan Sehat
- Input "Jenis Konsumsi": placeholder "Contoh: Snack Jajanan Pasar"
- Input "Porsi/Jumlah": type number, placeholder "100"
- Input "Harga Satuan": prefix "Rp", format ribuan, placeholder "5.000"

- CARD KALKULASI (read-only, bg abu-abu muda #F3F4F6, rounded 12px, margin 16px 0):
  - Ikon 🧮 + "Total Biaya (otomatis):" label kecil
  - "Rp 500.000" — bold, besar, warna maroon
  - Teks kecil 11px: "100 × Rp 5.000 — dihitung otomatis, tidak bisa diedit"

- Select PJ Konsumsi (searchable)
- Input "Kru Pembantu": placeholder "Contoh: Bu Ani, Mbak Rina"
- Input "Perlengkapan Konsumsi": placeholder "Contoh: 15 tampah, 10 kendi"
- Dropdown Status Kesiapan: Belum Dipesan / Dipesan / Siap Hari H (badge warna)
- Input "Catatan/Vendor": placeholder "Contoh: Pesan dari Bu Tini, bayar H-3"

BOTTOM STICKY: Batal + Simpan (maroon 48px)
```

### Prompt E3 — Detail Konsumsi *(BARU)*

```
Aplikasi "SiPanitia", mobile viewport 375px, maroon + putih.

Buatkan HALAMAN DETAIL KONSUMSI mobile — data dummy:

- TOP BAR: back + "Snack Jajanan Pasar" + ikon ✏️
- BADGE STATUS: full-width, bg hijau muda, "Siap Hari H"

- KALKULASI CARD (prominent, bg maroon sangat muda/pink):
  - 🧮 "100 porsi × Rp 5.000"
  - "= Rp 500.000" bold besar

- INFO LIST:
  - Event: "Outbound" (badge)
  - Jenis: "Snack Jajanan Pasar (klepon, getuk, pisang goreng, onde-onde)"
  - PJ: avatar + "Bu Sari"
  - Kru Pembantu: "Bu Ani, Mbak Rina"
  - Perlengkapan: "15 tampah kecil, daun pisang"
  - Vendor/Catatan: "Pesan dari Bu Tini RT 03, bayar H-3"

- RIWAYAT PERUBAHAN: 2 entry
- BOTTOM STICKY: "Ubah Status" maroon
```

---

## F. Notifikasi

### Prompt F1 — Halaman Notifikasi

```
Aplikasi "SiPanitia", mobile viewport 375px, maroon + putih.

Buatkan HALAMAN NOTIFIKASI mobile:

- TOP BAR putih: judul "Notifikasi" + link kecil "Tandai semua dibaca" maroon di kanan
- TAB: "Semua" (aktif, underline maroon) | "Belum Dibaca" (badge angka "4")

- LIST NOTIFIKASI — 5 contoh card realistis:

  Card 1 (BELUM DIBACA — ada titik biru 8px di kiri):
  - 🔴 Ikon peringatan merah | "Tugas 'Sebar Undangan' sudah **terlambat 3 hari**" | "BPH · Pak Budi"
  - Timestamp: "1 jam lalu"

  Card 2 (BELUM DIBACA):
  - 🟡 Ikon jam kuning | "Tugas 'Booking Sound System' jatuh tempo **2 hari lagi**" | "Sie Perkap · Mas Andi"
  - "3 jam lalu"

  Card 3 (BELUM DIBACA):
  - 🔴 Ikon peringatan | "Barang 'Sound System' **deadline kumpul terlewat** 4 hari" | "Sie Perkap"
  - "5 jam lalu"

  Card 4 (SUDAH DIBACA — tanpa titik biru, teks sedikit pudar):
  - 🟡 Ikon jam | "Tugas 'Pesan Tumpeng' jatuh tempo **2 hari lagi**" | "Sie Konsumsi · Bu Sari"
  - "Kemarin, 07:00"

  Card 5 (SUDAH DIBACA):
  - 🟢 Ikon check hijau | "Pak Budi menyelesaikan tugas 'Buat Proposal Kegiatan'" | "BPH"
  - "2 hari lalu"

- Tap notifikasi → buka detail tugas/barang terkait
- Tidak ada bottom nav (notifikasi dibuka dari ikon lonceng di top bar, bukan tab)
```

### Prompt F1-empty — Empty State Notifikasi

```
Aplikasi "SiPanitia", mobile viewport 375px, maroon + putih.

Buatkan EMPTY STATE NOTIFIKASI:
- Top bar sama seperti F1
- Ilustrasi: ikon lonceng besar (outline, maroon muda) dengan tanda centang, ~100×100px
- "Tidak ada notifikasi" bold 16px
- "Semua tugas on track! 🎉" abu-abu 14px
```

---

## G. Admin — Manajemen (khusus Super Admin)

### Prompt G1 — Manajemen User

```
Aplikasi "SiPanitia", mobile viewport 375px, maroon + putih. Halaman khusus Super Admin.

Buatkan HALAMAN MANAJEMEN USER mobile:

- TOP BAR putih: back + "Manajemen User"
- SEARCH BAR: "Cari user..."
- LIST CARD — 4 contoh:

  Card 1:
  - Avatar + "Pak Ahmad" bold | badge maroon "Super Admin"
  - Email: pak.ahmad@email.com
  - Divisi: chip "BPH" (maroon outline) + chip "Koordinator" (filled maroon)
  - Toggle: ● Aktif (hijau)

  Card 2:
  - Avatar + "Mas Andi" | badge biru "Koordinator Sie"
  - Email: andi@email.com
  - Divisi: chip "Sie Perkap" + chip "Koordinator"
  - Toggle: ● Aktif

  Card 3:
  - Avatar + "Bu Sari" | badge abu "Anggota Sie"
  - Email: sari@email.com
  - Divisi: chip "Sie Konsumsi" + chip "Sie Acara" (merangkap 2 divisi)
  - Toggle: ● Aktif

  Card 4:
  - Avatar + "Pak Joko" | badge abu muda "Viewer"
  - Email: joko@email.com
  - Divisi: - (tidak ada)
  - Toggle: ○ Nonaktif (abu-abu, card sedikit pudar)

- FAB "+" tambah user

- BOTTOM SHEET (tampilkan sebagai state kedua di bawah, separator "— saat tap card —"):
  - Judul "Edit User: Mas Andi"
  - Input Nama, Input Email
  - Dropdown Role: Super Admin / BPH / Koordinator Sie / Anggota Sie / Viewer
  - Multi-select chip Divisi: chip-chip divisi bisa ditap untuk select/deselect, yang terpilih ada toggle kecil "Jadikan Koordinator" muncul di bawahnya
  - Tombol "Simpan" maroon + teks merah kecil "Nonaktifkan User" di bawahnya
```

### Prompt G2 — Manajemen Divisi

```
Aplikasi "SiPanitia", mobile viewport 375px, maroon + putih. Khusus Super Admin.

Buatkan HALAMAN MANAJEMEN DIVISI mobile:

- TOP BAR: back + "Manajemen Divisi"
- LIST CARD — 5 contoh:
  - "BPH" — "Koordinasi umum, administrasi, keuangan" — 3 anggota — ikon ✏️
  - "Sie Humas" — "Sosialisasi, komunikasi warga" — 2 anggota — ✏️
  - "Sie Dekdok" — "Dekorasi & dokumentasi" — 4 anggota — ✏️
  - "Sie Acara" — "Perencanaan & pelaksanaan lomba/kegiatan" — 5 anggota — ✏️
  - "Sie Perkap" — "Logistik, pinjam/beli barang" — 3 anggota — ✏️
- FAB "+" tambah divisi

- BOTTOM SHEET edit (tampilkan sebagai state kedua):
  - Input "Nama Divisi"
  - Textarea "Fokus Utama Tugas"
  - Textarea "Keterangan Integrasi" (placeholder: "Contoh: Berkoordinasi dengan Sie Acara untuk kebutuhan perlengkapan")
  - Tombol "Simpan" maroon
```

### Prompt G3 — Import Data Excel

```
Aplikasi "SiPanitia", mobile viewport 375px, maroon + putih. Khusus Super Admin.

Buatkan HALAMAN IMPORT DATA EXCEL mobile — tampilkan 2 STATE (atas-bawah, dengan separator):

STATE 1 — Upload:
- TOP BAR: back + "Import Data dari Excel"
- Card upload besar (dashed border maroon, rounded 16px, height ~180px): ikon file Excel besar di tengah, teks "Tap untuk pilih file .xlsx", teks kecil abu "Format harus sesuai template Format_Tugas_Kepanitiaan.xlsx"
- Tombol "Upload & Preview" full-width maroon (tampilkan dalam state DISABLED — abu-abu — sampai file dipilih)

— separator: "setelah file dipilih & diupload" —

STATE 2 — Preview:
- Header section: "Preview Data"
- SUMMARY CARD: 3 angka besar: "120" (total baris), "115" (valid, hijau), "5" (gagal, merah) — layout horizontal
- LIST CARD preview per baris (tampilkan 3 contoh):
  - ✅ Baris valid: "Buat Proposal Kegiatan" — BPH — 12 Jul 2026 — check hijau
  - ✅ Baris valid: "Booking Sound System" — Sie Perkap — 14 Jul 2026 — check hijau
  - ❌ Baris gagal: "Sebar Undangan" — BPH — "tanggal: ??" — border merah, teks merah "Format tanggal tidak valid"
- Tombol "Import 115 Baris Valid" maroon + "Batalkan" outline
```

---

## H. Profil & Pengaturan

### Prompt H1 — Halaman Profil

```
Aplikasi "SiPanitia", mobile viewport 375px, maroon + putih. Tab "Profil" aktif di bottom nav.

Buatkan HALAMAN PROFIL mobile:

- TOP SECTION (bg gradient maroon → dark): avatar besar (64px) putih, "Pak Ahmad" putih bold 20px, badge "Super Admin" pill putih semi-transparan, chips divisi di bawah: "BPH" "Koordinator"
- MENU LIST (card putih, rounded-top overlap):
  - 🔑 "Ubah Password" → chevron kanan
  - 📱 "Sesi Aktif" → keterangan kecil "Login: Chrome · 2 jam lalu" → chevron
  - 📋 "Riwayat Aktivitas Saya" → chevron
  - 📖 "Bantuan & Panduan" → chevron
  - Divider
  - 🚪 "Keluar" — teks merah, tanpa background, tap area full-width
- Bottom nav: "Profil" aktif maroon
```

### Prompt H2 — Ubah Password *(BARU)*

```
Aplikasi "SiPanitia", mobile viewport 375px, maroon + putih.

Buatkan HALAMAN UBAH PASSWORD mobile (dari menu Profil, user sudah login):

- TOP BAR: back + "Ubah Password"
- Input "Password Saat Ini" height 48px, ikon show/hide
- Divider kecil
- Input "Password Baru" height 48px, ikon show/hide
- Helper text 12px: "Minimum 8 karakter"
- Input "Konfirmasi Password Baru" height 48px
- Tombol "Simpan Password" full-width maroon, height 48px
- Tidak ada bottom nav (sub-halaman dari Profil)
```

### Prompt H3 — Riwayat Aktivitas *(BARU)*

```
Aplikasi "SiPanitia", mobile viewport 375px, maroon + putih.

Buatkan HALAMAN RIWAYAT AKTIVITAS SAYA mobile — audit log personal:

- TOP BAR: back + "Riwayat Aktivitas Saya"
- FILTER: dropdown "7 hari terakhir ▼" / "30 hari" / "Semua"

- TIMELINE vertikal (garis abu di kiri, dot warna per event, dikelompokkan per tanggal):

  **Hari ini**
  - 🟡 14:30 — "Mengubah status 'Koordinasi Sponsor' dari Belum Mulai → Proses" — modul BPH
  - 🟢 10:15 — "Menambah barang 'Tali Tampar 50m'" — modul Inventaris

  **Kemarin**
  - 🟡 16:00 — "Mengubah status 'Sebar Undangan' dari Belum Mulai → Proses" — modul BPH
  - 🔵 09:30 — "Mengedit catatan tugas 'Buat Proposal Kegiatan'" — modul BPH

  **15 Juli 2026**
  - 🟢 11:00 — "Menambah item konsumsi 'Snack Jajanan Pasar'" — modul Konsumsi

- Setiap entry bisa di-tap untuk buka detail tugas/barang/konsumsi terkait
```

---

## Catatan Tambahan

### Data Dummy
Semua prompt sudah menyertakan contoh data dummy realistis. Jika Stitch menghasilkan placeholder generik ("Task 1", "John Doe"), re-generate dengan menambahkan: *"Gunakan nama-nama Indonesia seperti Pak Ahmad, Bu Sari, Mas Andi. Gunakan judul tugas terkait kepanitiaan HUT RI seperti 'Sebar Undangan', 'Booking Sound System', 'Pesan Tumpeng'. Gunakan harga dalam Rupiah."*

### Versi Desktop/Tablet
Kalau butuh versi desktop nanti:
- Ganti `deviceType: "MOBILE"` → `"DESKTOP"` di Stitch API
- Ganti "card view" → "tabel penuh dengan kolom lengkap"
- Ganti "bottom navigation" → "sidebar kiri dengan menu penuh"
- Ganti "FAB" → "tombol 'Tambah' di kanan atas tabel"
- Ganti "bottom sheet form" → "modal dialog tengah layar"

### Checklist Screen Coverage vs PRD

| PRD FR | Screen | Status |
|---|---|---|
| FR-1.1 Login | A1 | ✅ |
| FR-1.4 Lupa Password | A2, A3 | ✅ |
| FR-2.x Dashboard | B1 | ✅ |
| FR-3.x Checklist BPH | C1, C2, C5 | ✅ |
| FR-3.5 Checklist Acara | C3, C4, C5 | ✅ |
| FR-3.7 Bulk Update | C1 (multi-select) | ✅ |
| FR-4.x Inventaris | D1, D2, D3 | ✅ |
| FR-5.x Konsumsi | E1, E2, E3 | ✅ |
| FR-6.x Notifikasi | F1 | ✅ |
| FR-7.x Audit Log | C5/D3/E3 (riwayat), H3 | ✅ |
| FR-8.x Import Excel | G3 | ✅ |
| FR-1.2 Manajemen User | G1 | ✅ |
| Manajemen Divisi | G2 | ✅ |
| Profil & Ubah Password | H1, H2 | ✅ |
| Empty States | C1-empty, F1-empty | ✅ |