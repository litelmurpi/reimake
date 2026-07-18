# Product Requirements Document (PRD)
## SiPanitia — Sistem Informasi & Monitoring Kepanitiaan HUT RI

| | |
|---|---|
| **Versi Dokumen** | 1.3 (+ hasil audit, revisi model data, strategi TDD) |
| **Tanggal** | 18 Juli 2026 |
| **Penulis** | Azfa |
| **Status** | Draft — Post-Audit Revision |
| **Tech Stack Target** | Laravel 13, MySQL/PostgreSQL, Livewire 3 + Blade, Tailwind CSS |

---

## 1. Latar Belakang

Saat ini monitoring kerja panitia HUT RI (BPH, Sie Acara, Sie Perkap, Sie Konsumsi, dll) dilakukan melalui satu file Excel (`Format_Tugas_Kepanitiaan.xlsx`) dengan 5 sheet: **Dashboard**, **BPH/Humas/Dekdok**, **Sie Acara**, **Sie Perkap (Logistik)**, dan **Sie Konsumsi**. File ini sudah cukup fungsional (dropdown status, formula total biaya, conditional formatting), tapi punya keterbatasan:

- Hanya bisa diedit satu file dalam satu waktu → rawan konflik version (siapa update terakhir, file mana yang paling baru).
- Tidak ada akses granular — semua orang bisa mengubah data sie lain.
- Tidak ada notifikasi otomatis untuk deadline yang lewat.
- Tidak bisa diakses/diupdate mudah dari HP saat di lapangan.
- Riwayat perubahan (siapa mengubah apa, kapan) tidak tercatat.

**SiPanitia** adalah aplikasi web berbasis Laravel yang mereplikasi seluruh fungsi file Excel tersebut sebagai sistem multi-user, dengan role per divisi, dashboard progress real-time, dan riwayat aktivitas.

---

## 2. Tujuan Produk

1. Memindahkan seluruh checklist tugas 5 sie ke sistem terpusat yang bisa diakses banyak orang secara bersamaan.
2. Memberi setiap divisi akses hanya ke data miliknya, sementara BPH/Ketua Panitia bisa melihat semua.
3. Menyediakan dashboard progress otomatis (seperti Progress Tracker di Excel) tanpa perlu hitung manual.
4. Mengirim reminder/notifikasi saat deadline tugas mendekati atau sudah lewat.
5. Menyimpan histori setiap perubahan status/data (audit trail).
6. Bisa diakses dari HP (responsive) untuk update cepat saat di lapangan.

### Non-Goals (di luar cakupan v1)
- Tidak membangun modul keuangan/akuntansi penuh (donasi, kas RT) — hanya estimasi biaya per item seperti di Excel.
- Tidak ada native mobile app (cukup responsive web).
- Tidak terintegrasi otomatis ke WhatsApp Group (notifikasi cukup di dalam web + email opsional).

---

## 3. Target Pengguna & Role

| Role | Deskripsi | Akses |
|---|---|---|
| **Super Admin / Ketua Panitia** | Pemilik sistem, biasanya Ketua RT/RW atau Ketua Panitia | Full akses semua modul, kelola user, kelola divisi |
| **BPH (Pengurus Harian)** | Sekretaris/Bendahara/Wakil | CRUD penuh di modul BPH, Humas, Dekdok; read-only di modul lain |
| **Koordinator Sie** | Ketua tiap sie (Acara, Perkap, Konsumsi) | CRUD penuh di modul sie-nya sendiri |
| **Anggota Sie** | Anggota biasa | Bisa update status tugas & isi catatan, tidak bisa hapus/tambah baris baru |
| **Viewer (opsional)** | Warga/donatur yang diberi akses lihat progress | Read-only ke Dashboard saja |

Autentikasi menggunakan Laravel Breeze/Fortify, otorisasi menggunakan **Spatie Laravel-Permission** (role & permission per divisi).

---

## 4. Pemetaan Modul (Sheet Excel → Modul Aplikasi)

| Sheet Excel | Modul Laravel | Keterangan |
|---|---|---|
| Dashboard & Panduan | `Dashboard` | Progress tracker live, panduan penggunaan, ringkasan divisi |
| BPH, Humas & Dekdok | `AdminTask` | Checklist administrasi, humas, dekorasi/dokumentasi |
| Sie Acara | `EventTask` | Checklist detail rundown per sub-event (Lomba Anak, Lomba Ibu-Ibu, Outbound, Tirakatan, Jalan Sehat) |
| Sie Perkap (Logistik) | `Inventory` | Logsheet peminjaman/pembelian & pengembalian barang |
| Sie Konsumsi | `Consumption` | Kebutuhan konsumsi per acara + estimasi biaya otomatis |

---

## 5. Functional Requirements

### 5.1 Modul Autentikasi & User Management
- FR-1.1: User login dengan email/username + password.
- FR-1.2: Super Admin dapat menambah/mengedit/menonaktifkan user dan menetapkan role + divisi.
- FR-1.3: Setiap user hanya melihat menu sesuai role-nya (sidebar dinamis).
- FR-1.4: *(BARU — hasil audit)* Fitur **reset/lupa password** via email. User bisa request reset link dari halaman login tanpa perlu menghubungi Super Admin.
- FR-1.5: *(BARU — hasil audit)* **Laravel Policy per model** memastikan user divisi X tidak bisa mengakses/memanipulasi data divisi Y meskipun via URL langsung (pencegahan IDOR).

### 5.2 Modul Dashboard
- FR-2.1: Menampilkan kartu ringkasan per divisi: **Total Tugas**, **Selesai**, **% Progress** — dihitung otomatis dari data live (setara formula `COUNTA`/`COUNTIF` di Excel). **AC:** Progress = Selesai/Total × 100 dibulatkan integer. Angka ter-update dalam ≤60 detik setelah status berubah. Warna kartu: hijau ≥80%, kuning 50-79%, merah <50%.
- FR-2.2: Progress bar visual per divisi (mirip data bar Excel) dan progress keseluruhan panitia.
- FR-2.3: Widget "Tugas Terlambat" — daftar semua tugas lintas divisi yang `target_tanggal < hari ini` dan status belum "Selesai", diurutkan dari yang paling lama telat.
- FR-2.4: Panduan penggunaan (statis, editable oleh Super Admin) menggantikan sheet "Panduan Penggunaan".
- FR-2.5: Tabel ringkasan divisi & tanggung jawab (statis/editable), mereplikasi tabel "Daftar Divisi & Responsibility".
- FR-2.6: *(BARU — hasil audit)* **Widget Total Anggaran** — menampilkan total estimasi biaya gabungan Perkap + Konsumsi, setara total biaya di Excel yang sebelumnya dihitung manual.

### 5.3 Modul Checklist Tugas (BPH/Humas/Dekdok & Sie Acara)
- FR-3.1: CRUD baris tugas dengan field: No (auto-increment), Divisi/Sub-kegiatan, Nama Tugas, Target Tanggal, Penanggung Jawab (PJ), Status, Catatan.
- FR-3.2: Kolom Status berupa dropdown/select dengan pilihan tetap: `Belum Mulai`, `Proses`, `Selesai`, `Tertunda` — badge warna otomatis sesuai nilai, setara conditional formatting Excel. *(Revisi audit — pemetaan warna diperjelas:)* **Abu-abu** = Belum Mulai, **Kuning** = Proses, **Hijau** = Selesai, **Oranye** = Tertunda. Merah **hanya** digunakan untuk highlight overdue (FR-3.3), bukan untuk status.
- FR-3.3: Baris dengan `target_tanggal` terlewati dan status ≠ Selesai otomatis diberi highlight merah di tabel.
- FR-3.4: Sortir & filter per kolom (status, PJ, tanggal) — setara AutoFilter Excel.
- FR-3.5: Sie Acara memiliki field tambahan: Sub-Event, MC/Pengisi, Perlengkapan Utama, Detail Aturan/Rute/Deskripsi, Catatan Plan B/Resiko.
- FR-3.6: Export tabel ke Excel/PDF (opsional, untuk laporan ke pihak eksternal).
- FR-3.7: *(BARU — hasil audit)* **Bulk update status** — user bisa memilih beberapa baris tugas sekaligus dan mengubah statusnya dalam satu aksi (untuk efisiensi saat selesai rapat/koordinasi).

### 5.4 Modul Inventaris/Perkap
- FR-4.1: CRUD data barang dengan field: Nama Barang, Divisi Peminta, Sistem Pengadaan (`Pinjam`/`Beli`/`Inventaris Milik Sendiri`), Tanggal Pinjam/Beli, Deadline Kumpul, Tanggal Kembali, PJ Peminjam, Pihak Dipinjami/Toko, Estimasi Biaya, Posisi Barang Terkini, Status Ceklis, Catatan. *(Revisi audit — klarifikasi Status Ceklis:)* `Tersedia` = inventaris milik sendiri yang siap pakai. `Belum Kembali` = barang pinjaman yang belum dikembalikan. `Sudah Kembali` = barang pinjaman yang sudah dikembalikan. `Habis Pakai` = barang habis pakai (beli) yang sudah terpakai.
- FR-4.2: Highlight merah otomatis untuk barang yang `deadline_kumpul` terlewat dan status masih `Belum Kembali`.
- FR-4.3: Total estimasi biaya perkap dihitung otomatis (`SUM`) dan ditampilkan di footer tabel & dashboard.
- FR-4.4: *(BARU — hasil audit)* **Filter cepat by status pengadaan** — tombol tab/filter untuk melihat hanya barang "Pinjam" yang belum kembali, hanya barang "Beli", dll.

### 5.5 Modul Konsumsi
- FR-5.1: CRUD data konsumsi dengan field: Nama Acara/Kegiatan, Jenis Konsumsi, Porsi/Jumlah, Harga Satuan, **Total Biaya (otomatis = Jumlah × Harga Satuan, read-only, dihitung backend)**, PJ Konsumsi, Kru Pembantu, Perlengkapan Konsumsi, Status Kesiapan (`Belum Dipesan`/`Dipesan`/`Siap Hari H`), Catatan/Vendor.
- FR-5.2: Total estimasi anggaran konsumsi otomatis terjumlah di footer & tersinkron ke dashboard.

### 5.6 Modul Notifikasi
- FR-6.1: Sistem mengirim notifikasi in-app (dan opsional email) H-2 sebelum `target_tanggal`/`deadline` sebuah tugas/barang.
- FR-6.2: Notifikasi tugas yang sudah lewat deadline dan belum selesai, dikirim ke PJ terkait dan Super Admin.

### 5.7 Modul Audit Log
- FR-7.1: Setiap perubahan status/data dicatat: siapa, kapan, field apa, nilai lama → baru (menggunakan package seperti `spatie/laravel-activitylog`).
- FR-7.2: Riwayat perubahan bisa dilihat per baris tugas (mirip "siapa update terakhir").
- FR-7.3: *(BARU — hasil audit)* **Inline "last modified by"** — setiap baris di tabel checklist menampilkan nama user + timestamp perubahan terakhir secara langsung (tanpa harus buka halaman log terpisah).

### 5.8 Modul Import Data *(BARU — hasil audit)*
- FR-8.1: **Import data awal dari Excel** — Super Admin bisa mengupload file `Format_Tugas_Kepanitiaan.xlsx` untuk migrasi data awal ke sistem. Mapping kolom Excel → field database ditangani otomatis berdasarkan pemetaan di Section 4.
- FR-8.2: Validasi import — sistem menampilkan preview data sebelum disimpan, dengan highlight baris yang gagal validasi.

---

## 6. Model Data (Ringkasan Entitas) — *Revisi hasil audit*

> **Catatan audit:** Model data di bawah sudah direvisi dari v1.2. Perubahan utama:
> 1. Relasi User ↔ Divisi diubah dari `divisi_id` (one-to-many) ke **pivot table** `divisi_user` (many-to-many), karena anggota panitia sering merangkap divisi.
> 2. ENUM pada `AdminTask.divisi` diganti **foreign key** ke tabel `Divisi`, agar mudah di-maintain jika ada divisi baru.
> 3. Ditambahkan entitas **`Event`** sebagai parent entity yang menghubungkan task, konsumsi, dan inventory ke acara tertentu.
> 4. `total_biaya` di ConsumptionItem disimpan di DB via **model observer** (bukan accessor), untuk performa aggregate/SUM.

```
User
 ├─ id, name, email, password, role
 ├─ (relasi divisi via pivot table divisi_user)

Divisi
 ├─ id, nama_divisi, fokus_utama, keterangan_integrasi

divisi_user          (pivot table, many-to-many)
 ├─ user_id (FK → User)
 ├─ divisi_id (FK → Divisi)
 ├─ is_koordinator (boolean, default false)

Event                (BARU — entitas induk acara)
 ├─ id, nama_event, tanggal, deskripsi, status

AdminTask            (BPH, Humas, Dekdok)
 ├─ id, divisi_id (FK → Divisi)    ← diganti dari ENUM
 ├─ nama_tugas, target_tanggal, pj_user_id (FK → User)
 ├─ status, catatan
 ├─ last_modified_by (FK → User, nullable) ← untuk FR-7.3 inline display
 ├─ last_modified_at (timestamp, nullable)

EventTask            (Sie Acara)
 ├─ id, event_id (FK → Event, nullable)
 ├─ sub_event, kebutuhan_aspek, target_tanggal, pj_user_id (FK → User)
 ├─ mc_pengisi, perkap_utama, detail_deskripsi, status, catatan_plan_b
 ├─ last_modified_by, last_modified_at

InventoryItem        (Sie Perkap)
 ├─ id, event_id (FK → Event, nullable)
 ├─ nama_barang, divisi_peminta, sistem_pengadaan
 ├─ tgl_pinjam_beli, deadline_kumpul, tgl_kembali
 ├─ pj_peminjam_user_id (FK → User), pihak_dipinjami
 ├─ estimasi_biaya, posisi_barang, status_ceklis, catatan
 ├─ last_modified_by, last_modified_at

ConsumptionItem      (Sie Konsumsi)
 ├─ id, event_id (FK → Event, nullable)    ← menggantikan kolom nama_acara string
 ├─ jenis_konsumsi, porsi_jumlah, harga_satuan
 ├─ total_biaya (stored, dihitung via observer saat insert/update)
 ├─ pj_konsumsi_user_id (FK → User), kru_pembantu
 ├─ perlengkapan, status_kesiapan, catatan_vendor
 ├─ last_modified_by, last_modified_at

ActivityLog (via spatie/laravel-activitylog)
 ├─ id, subject_type, subject_id, causer_id, event, changes, created_at
```

---

## 7. Non-Functional Requirements

| Kategori | Ketentuan |
|---|---|
| **Framework** | Laravel 13, PHP ^8.3 |
| **Frontend** | Livewire 3 + Blade + Tailwind CSS (tanpa perlu SPA terpisah, sesuai skala aplikasi internal panitia) |
| **Database** | MySQL 8 / PostgreSQL 15 |
| **Autentikasi** | Laravel Breeze (Livewire stack) |
| **Otorisasi** | spatie/laravel-permission |
| **Audit Trail** | spatie/laravel-activitylog |
| **Responsif** | Wajib mobile-friendly (breakpoint Tailwind `sm`, `md`, `lg`) karena update sering dilakukan dari HP di lapangan. Tabel dengan banyak kolom (Inventory 12 field) menggunakan **card view** di breakpoint `sm` |
| **Performa** | Query dashboard (COUNT per divisi) di-cache 60 detik agar tidak query ulang tiap load |
| **Keamanan** | *(Diperkuat hasil audit)* CSRF protection bawaan Laravel, validasi form request per modul, rate limiting di route login, **Laravel Policy per model** (cegah IDOR), session timeout 8 jam, minimum password 8 karakter, backup database ter-enkripsi |
| **Testing** | *(BARU)* Metode **TDD (Test-Driven Development)** — lihat Section 14 untuk strategi lengkap |
| **Hosting** | Shared hosting/VPS kecil cukup (low-traffic, internal RT/RW) |

---

## 8. Alur Pengguna Utama (User Flow Singkat)

1. User login → diarahkan ke Dashboard sesuai role.
2. Anggota Sie Acara membuka menu "Sie Acara" → melihat tabel tugas divisinya saja → mengubah status salah satu baris dari `Belum Mulai` ke `Proses` → badge warna berubah otomatis → dashboard ter-update.
3. Koordinator Sie Perkap menambah barang baru yang dipinjam → mengisi deadline kumpul → sistem otomatis membuat reminder H-2.
4. Ketua Panitia membuka Dashboard → melihat progress keseluruhan (%), melihat daftar tugas telat lintas divisi, klik salah satu → diarahkan langsung ke baris tugas terkait.

---

## 9. Rencana Pengembangan (Milestone) — *Revisi timeline hasil audit*

> **Catatan audit:** Estimasi v1.2 (14-18 hari) terlalu agresif untuk solo developer + learning curve Laravel 13. Estimasi direvisi dengan memperhitungkan TDD overhead (~25-30% tambahan waktu, tapi menghemat waktu debugging di fase akhir). Timeline tetap masuk target H-Day 17 Agustus (30 hari dari sekarang).

| Fase | Cakupan | Estimasi | TDD Focus |
|---|---|---|---|
| **Fase 1** | Setup Laravel 13, auth (Breeze + reset password), role & permission, struktur database & migration semua modul, seeding | 4–5 hari | Unit test: model relations, policy authorization |
| **Fase 2** | CRUD modul AdminTask, EventTask (checklist + status badge + filter + bulk update) | 5–6 hari | Feature test: CRUD operations, status transitions, policy enforcement |
| **Fase 3** | CRUD modul Inventory & Consumption (kalkulasi total biaya otomatis, filter status) | 4–5 hari | Unit test: kalkulasi biaya (observer), Feature test: CRUD + filter |
| **Fase 4** | Dashboard progress tracker + widget tugas telat + widget anggaran + notifikasi | 4–5 hari | Feature test: dashboard aggregation, Unit test: notification scheduling |
| **Fase 5** | Audit log, import Excel, export Excel/PDF, polish UI & responsive | 3–4 hari | Feature test: import/export, integration test end-to-end |
| **Buffer** | Bug fixing, edge cases dari testing, final polish | 2–3 hari | — |
| | **Total** | **22–28 hari** | |

---

## 10. Metrik Keberhasilan (Success Metrics)

- Seluruh 5 divisi menggunakan sistem ini sebagai satu-satunya sumber data (tidak balik ke Excel).
- Update status tugas tercatat real-time tanpa perlu "siapa pegang file terakhir".
- Jumlah tugas yang telat terdeteksi & ditindaklanjuti meningkat dibanding sistem Excel manual (berkat notifikasi H-2).
- Ketua Panitia bisa melihat progress keseluruhan kapan saja tanpa perlu minta laporan manual ke tiap sie.

---

## 11. Risiko & Mitigasi

| Risiko | Mitigasi |
|---|---|
| Anggota panitia tidak familiar pakai web app (terbiasa Excel/WA) | Buat UI sesederhana mungkin, onboarding singkat + video tutorial 2 menit |
| Tidak ada koneksi internet stabil saat di lapangan (outbound, dsb) | Prioritaskan modul kritikal untuk tetap ringan/cepat diakses dari mobile data |
| Hosting terbatas/gratisan | Optimasi query, gunakan cache, hindari asset frontend berat |

---

## 12. Strategi Deployment

Karena aplikasi ini bersifat **internal, skala kecil, dan low-traffic** (dipakai oleh puluhan panitia + beberapa warga viewer), pilihan deployment difokuskan pada **biaya rendah** dan **kemudahan maintenance**, bukan skalabilitas besar.

### 12.1 Opsi A — VPS Indonesia (Rekomendasi Utama)

| Provider | Estimasi Biaya | Kenapa Cocok |
|---|---|---|
| DomaiNesia VPS | ~Rp70.000–150.000/bulan | Data center Indonesia (latensi rendah untuk warga lokal), root access penuh, panel & dokumentasi Bahasa Indonesia |
| Niagahoster VPS | ~Rp70.000–150.000/bulan | Alternatif setara DomaiNesia, dokumentasi lengkap untuk pemula |
| Biznet Gio Cloud | Sedikit lebih mahal | Kalau butuh performa lebih tinggi / SLA lebih ketat |

**Kenapa direkomendasikan:** biaya dalam Rupiah, latensi rendah untuk pengguna lokal RT/RW (data center Jakarta, ~5-20ms vs 150-250ms VPS luar negeri), dan karena kamu sedang belajar Laravel — proses setup manual (Nginx, PHP-FPM, MySQL, Supervisor, SSL) jadi nilai tambah skill DevOps yang berguna untuk portofolio.

**Spek minimum yang disarankan:** jangan ambil paket termurah (1 vCPU/1GB RAM) — di 1GB, Nginx + PHP-FPM + MySQL + Redis berjalan bareng jadi terlalu mepet dan gampang swap saat ada lonjakan kecil. Ambil minimal **1 vCPU / 2GB RAM** (umumnya ~Rp70.000–100.000/bulan) supaya ada headroom yang cukup. vCPU di tier ini sifatnya *shared* (dipakai bareng VM lain di server fisik yang sama) — untuk traffic internal seperti SiPanitia (puluhan user, jarang akses bersamaan) ini bukan masalah, tapi perlu diketahui bukan dedicated core.

### 12.1.1 Opsi A2 — Oracle Cloud Always Free Tier (Gratis, Spek Besar)

Kalau ingin **benar-benar gratis** dengan performa yang justru lebih besar dari VPS berbayar di atas:

- **Always Free**, bukan trial — selama masih dalam batas pemakaian, tidak pernah ditagih.
- VM ARM (Ampere A1): 2 OCPU + 12 GB RAM, 200 GB storage, 10 TB bandwidth/bulan (per Juni 2026; sebelumnya 4 OCPU/24GB).
- Semua stack (Ubuntu, Nginx, PHP-FPM, MySQL) kompatibel ARM, jadi setup sama persis seperti Opsi A.

**Trade-off yang perlu diterima:**
- Wajib kartu kredit/debit saat daftar untuk verifikasi (walau tidak ditagih selama dalam batas gratis).
- Ketersediaan region kadang penuh, kadang perlu coba beberapa region Asia Pacific.
- Tidak ada SLA/support resmi — risiko downtime atau suspend akun ditanggung sendiri, tanpa bantuan cepat dari provider.
- Setup manual sepenuhnya, tidak ada kemudahan git-push-deploy seperti Laravel Cloud.

Cocok kalau kamu nyaman menanggung risiko di atas demi biaya nol dan mau belajar sekaligus punya spek besar untuk latihan.

**Stack di VPS:**
- Ubuntu 22.04/24.04 LTS
- Nginx sebagai web server + reverse proxy
- PHP-FPM 8.3 (sesuai requirement Laravel 13)
- MySQL 8 (bisa juga MariaDB)
- Supervisor untuk menjaga queue worker (`php artisan queue:work`) tetap hidup
- Certbot (Let's Encrypt) untuk SSL gratis
- Git untuk deploy (`git pull` + `composer install --no-dev` + `php artisan migrate --force` di script deploy)

### 12.2 Opsi B — Laravel Cloud (Kalau Waktu Mepet / Mau Zero Server Management)

Laravel resmi punya platform **Laravel Cloud** — PaaS serverless dari tim Laravel sendiri.

- Starter plan: **$5/bulan** (dapat $5 kredit pemakaian, bulan pertama gratis untuk akun baru).
- **Scale-to-zero** aktif secara default → kalau aplikasi jarang diakses (misalnya cuma dibuka pas ada update tugas), biaya jadi sangat efisien.
- Deploy langsung dari Git (push ke branch → otomatis live), tanpa perlu setup Nginx/PHP manual.
- Termasuk database MySQL/Postgres serverless dan custom domain.
- Cocok kalau target rilis mepet (mis. H-beberapa hari sebelum acara) dan tidak ada waktu untuk konfigurasi server dari nol.

**Trade-off:** biaya dalam USD (perlu kartu kredit/virtual card), dan kamu belajar lebih sedikit soal server administration dibanding Opsi A.

### 12.3 Rekomendasi

| Kondisi | Pilihan |
|---|---|
| Ada waktu 1–2 minggu, ingin belajar server + hemat jangka panjang + kepastian (tanpa risiko suspend) | **VPS Indonesia, min. 2GB RAM (DomaiNesia/Niagahoster)** |
| Mau benar-benar gratis, spek besar, dan siap menanggung risiko region penuh/suspend akun | **Oracle Cloud Always Free Tier** |
| Waktu sangat terbatas, prioritas cepat live & minim maintenance | **Laravel Cloud** |

Untuk kedua opsi, tetap pasang **Cloudflare (free plan)** di depan domain — membantu SSL/CDN gratis, sedikit meringankan beban server, dan proteksi dasar dari traffic mencurigakan.

---

## 13. Strategi Performa

Beban aplikasi ini kecil (puluhan user, data ratusan baris per sheet), jadi fokus performa bukan pada "menahan traffic besar", tapi pada **respons cepat saat panitia update status di lapangan** (sering pakai HP dengan koneksi seluler yang tidak selalu stabil) dan **query dashboard yang ringan**.

### 13.1 Level Database
- Tambahkan index pada kolom yang sering difilter/disortir: `status`, `target_tanggal`/`deadline_kumpul`, `divisi_id`, `pj_user_id`.
- Gunakan **eager loading** (`with()`) untuk relasi (misalnya task → user PJ) agar tidak kena N+1 query saat render tabel.
- Paginasi semua tabel checklist (misalnya 20–25 baris per halaman) — walau datanya kecil, ini kebiasaan baik dan bikin loading tabel konsisten cepat.
- Kolom `total_biaya` di Konsumsi dihitung di level aplikasi (accessor/computed) saat insert/update, bukan dihitung ulang tiap kali tabel di-render.

### 13.2 Level Caching
- Gunakan **Redis** (kalau VPS mendukung) atau file cache untuk:
  - Angka ringkasan Dashboard (`Total Tugas`, `Selesai`, `% Progress` per divisi) — cache 60 detik, di-invalidate otomatis begitu ada task yang statusnya diubah (`Cache::forget` di observer/event).
  - Session & queue driver pakai Redis juga kalau tersedia, supaya lebih ringan dibanding driver `file`/`database`.

### 13.3 Level Background Job
- Pengecekan tugas overdue & pengiriman notifikasi **tidak dilakukan real-time saat halaman dibuka**, tapi lewat **Laravel Scheduler** (`schedule:run` via cron VPS) yang jalan sekali sehari (misalnya jam 07:00) → hasilnya di-cache, ditampilkan di Dashboard tanpa query berat berulang.
- Pengiriman email reminder pakai **Queue** (`php artisan queue:work` dijaga Supervisor) supaya tidak memperlambat request user saat menyimpan data.

### 13.4 Level Frontend
- Pakai **Vite** (bawaan Laravel) untuk build asset — otomatis minify JS/CSS.
- Tailwind CSS di-compile dengan JIT mode supaya file CSS final kecil (hanya class yang benar-benar dipakai).
- Hindari load library JS berat yang tidak perlu; Livewire sudah cukup untuk interaktivitas tabel/status tanpa perlu framework SPA penuh.

### 13.5 Level Server/PHP
- Aktifkan **OPcache** di `php.ini` (`opcache.enable=1`) — signifikan mempercepat eksekusi PHP di VPS.
- Kalau performa masih terasa kurang di masa depan (skala bertambah besar, dipakai lintas RT/RW), pertimbangkan **Laravel Octane** (dengan Swoole/RoadRunner) — tidak wajib di v1 karena beban masih kecil, cukup dicatat sebagai opsi upgrade.

### 13.6 Monitoring & Backup
- **Laravel Pulse** (bawaan ekosistem Laravel) untuk memantau request lambat & query berat langsung dari dashboard admin.
- **UptimeRobot** (free tier) untuk cek availability aplikasi dari luar, kirim notifikasi kalau server down.
- Backup database otomatis harian (cron `mysqldump` disimpan ke storage terpisah/S3-compatible) — penting karena ini satu-satunya sumber data panitia, tidak boleh hilang mendekati hari-H.

---

## 14. Strategi Test-Driven Development (TDD)

Pengembangan SiPanitia menggunakan metode **TDD (Test-Driven Development)** dengan siklus **Red → Green → Refactor** untuk setiap fitur. Ini memastikan setiap modul memiliki test coverage sebelum kode produksi ditulis.

### 14.1 Prinsip TDD yang Diterapkan

1. **Red:** Tulis test yang gagal terlebih dahulu — test mendefinisikan *apa* yang harus dilakukan fitur.
2. **Green:** Tulis kode minimum yang membuat test tersebut pass.
3. **Refactor:** Perbaiki struktur kode tanpa mengubah behavior (test tetap pass).
4. **Tidak ada kode produksi tanpa test yang mendahuluinya.**

### 14.2 Jenis Test & Tools

| Jenis Test | Tool | Cakupan | Kapan Ditulis |
|---|---|---|---|
| **Unit Test** | PHPUnit (bawaan Laravel) | Model (relasi, accessor, mutator, scope), Service class, kalkulasi bisnis | Sebelum menulis model/service |
| **Feature Test** | PHPUnit + Laravel HTTP Testing | Controller, Livewire component, middleware, authorization | Sebelum menulis controller/component |
| **Browser Test** | Laravel Dusk (opsional, jika waktu cukup) | Alur end-to-end kritis (login → update status → cek dashboard) | Setelah Fase 4 selesai |

### 14.3 Test Plan per Modul

#### Modul Auth & Permission
```
tests/
├── Unit/
│   ├── Models/UserTest.php
│   │   ├── test_user_belongs_to_many_divisi
│   │   ├── test_user_has_role
│   │   └── test_user_can_be_deactivated
│   └── Models/DivisiTest.php
│       ├── test_divisi_has_many_users
│       └── test_divisi_has_koordinator
├── Feature/
│   ├── Auth/LoginTest.php
│   │   ├── test_user_can_login_with_valid_credentials
│   │   ├── test_user_cannot_login_with_invalid_credentials
│   │   ├── test_login_is_rate_limited
│   │   └── test_user_can_reset_password
│   └── Auth/AuthorizationTest.php
│       ├── test_anggota_sie_cannot_access_other_divisi_data
│       ├── test_super_admin_can_access_all_divisi
│       ├── test_koordinator_can_crud_own_divisi
│       └── test_anggota_can_only_update_status_not_delete
```

#### Modul AdminTask & EventTask
```
tests/
├── Unit/
│   ├── Models/AdminTaskTest.php
│   │   ├── test_admin_task_belongs_to_divisi
│   │   ├── test_admin_task_belongs_to_pj_user
│   │   ├── test_admin_task_is_overdue_scope
│   │   ├── test_admin_task_status_must_be_valid_enum_value
│   │   └── test_last_modified_tracking
│   └── Models/EventTaskTest.php
│       ├── test_event_task_belongs_to_event
│       └── test_event_task_has_additional_fields
├── Feature/
│   ├── AdminTask/CreateAdminTaskTest.php
│   │   ├── test_koordinator_can_create_task_in_own_divisi
│   │   ├── test_koordinator_cannot_create_task_in_other_divisi
│   │   ├── test_anggota_cannot_create_task
│   │   └── test_create_task_requires_valid_data
│   ├── AdminTask/UpdateStatusTest.php
│   │   ├── test_anggota_can_update_status_only
│   │   ├── test_status_change_updates_last_modified
│   │   ├── test_status_change_invalidates_dashboard_cache
│   │   └── test_bulk_status_update_works_correctly
│   └── AdminTask/FilterSortTest.php
│       ├── test_can_filter_by_status
│       ├── test_can_filter_by_pj
│       ├── test_can_sort_by_target_tanggal
│       └── test_overdue_tasks_have_red_highlight_flag
```

#### Modul Inventory
```
tests/
├── Unit/
│   └── Models/InventoryItemTest.php
│       ├── test_inventory_item_belongs_to_pj_user
│       ├── test_inventory_is_overdue_when_deadline_passed_and_not_returned
│       ├── test_total_estimasi_biaya_scope
│       └── test_status_ceklis_valid_values
├── Feature/
│   ├── Inventory/CrudInventoryTest.php
│   │   ├── test_koordinator_perkap_can_create_item
│   │   ├── test_validation_requires_nama_barang_and_sistem_pengadaan
│   │   └── test_estimasi_biaya_must_be_numeric
│   └── Inventory/FilterInventoryTest.php
│       ├── test_can_filter_by_sistem_pengadaan
│       ├── test_can_filter_belum_kembali_only
│       └── test_sum_estimasi_biaya_displayed_in_footer
```

#### Modul Konsumsi
```
tests/
├── Unit/
│   └── Models/ConsumptionItemTest.php
│       ├── test_total_biaya_calculated_on_create
│       ├── test_total_biaya_recalculated_on_update
│       ├── test_total_biaya_equals_porsi_times_harga_satuan
│       └── test_total_anggaran_scope_sums_all_items
├── Feature/
│   └── Consumption/CrudConsumptionTest.php
│       ├── test_koordinator_konsumsi_can_create_item
│       ├── test_total_biaya_is_readonly_in_form
│       └── test_total_anggaran_synced_to_dashboard
```

#### Modul Dashboard
```
tests/
├── Feature/
│   └── Dashboard/DashboardTest.php
│       ├── test_dashboard_shows_progress_per_divisi
│       ├── test_dashboard_progress_is_cached
│       ├── test_cache_invalidated_on_task_status_change
│       ├── test_overdue_widget_shows_correct_tasks
│       ├── test_overdue_widget_sorted_by_oldest_first
│       ├── test_total_anggaran_widget_shows_combined_perkap_konsumsi
│       └── test_viewer_role_can_only_see_dashboard
```

#### Modul Notifikasi
```
tests/
├── Unit/
│   └── Notifications/DeadlineReminderTest.php
│       ├── test_reminder_sent_h_minus_2_before_deadline
│       ├── test_no_reminder_for_completed_tasks
│       └── test_overdue_notification_sent_to_pj_and_super_admin
├── Feature/
│   └── Notification/ScheduledNotificationTest.php
│       ├── test_daily_scheduler_checks_upcoming_deadlines
│       └── test_notification_queued_not_sent_synchronously
```

#### Modul Import Data
```
tests/
├── Feature/
│   └── Import/ExcelImportTest.php
│       ├── test_super_admin_can_upload_excel_file
│       ├── test_non_admin_cannot_import
│       ├── test_invalid_file_format_rejected
│       ├── test_preview_shows_parsed_data_before_commit
│       └── test_import_creates_correct_records_in_database
```

### 14.4 Konvensi & Aturan Testing

1. **Penamaan test:** `test_<apa_yang_ditest>` — deskriptif, bisa dibaca sebagai kalimat.
2. **Setiap test independen:** Gunakan `RefreshDatabase` trait agar setiap test mulai dari database bersih.
3. **Factory & Seeder:** Buat `Factory` untuk setiap model (User, Divisi, AdminTask, dll) agar test data konsisten.
4. **Assertasi yang tegas:** Gunakan `assertDatabaseHas`, `assertStatus`, `assertSee`, `assertRedirect` — bukan hanya `assertTrue`.
5. **Jalankan test sebelum commit:** `php artisan test` harus pass 100% sebelum push.
6. **Coverage target:** Minimum **80% line coverage** untuk model dan controller. Dashboard & notification boleh lebih rendah (60%) karena melibatkan caching.

### 14.5 Workflow TDD Harian

```
1. Pilih 1 FR dari backlog
2. Tulis test(s) yang mendefinisikan behavior FR tersebut
3. Jalankan test → MERAH (gagal)
4. Tulis kode minimum di model/controller/component
5. Jalankan test → HIJAU (pass)
6. Refactor kode jika perlu (test tetap pass)
7. Commit: "feat: FR-X.X — [deskripsi], tests passing"
8. Ulangi untuk FR berikutnya
```

---

## 15. Hasil Audit PRD & Revisi yang Diterapkan

> **Audit dilakukan:** 18 Juli 2026 | **Skor keseluruhan:** 82/100 (Solid & Layak Dikerjakan)

### 15.1 Ringkasan Skor Audit

| Aspek | Skor | Status |
|---|---|---|
| Kejelasan masalah & tujuan | ⭐⭐⭐⭐⭐ | Tidak perlu revisi |
| Kelengkapan functional requirements | ⭐⭐⭐⭐ | ✅ Direvisi — 6 FR baru ditambahkan |
| Model data | ⭐⭐⭐⭐ | ✅ Direvisi — 4 perubahan struktural |
| Non-functional requirements | ⭐⭐⭐⭐ | ✅ Direvisi — keamanan diperkuat, TDD ditambahkan |
| Strategi deployment | ⭐⭐⭐⭐⭐ | Tidak perlu revisi |
| Strategi performa | ⭐⭐⭐⭐⭐ | Tidak perlu revisi |
| Keamanan | ⭐⭐⭐ → ⭐⭐⭐⭐ | ✅ Direvisi — Policy, session, password, backup |
| UX & mobile consideration | ⭐⭐⭐ → ⭐⭐⭐⭐ | ✅ Direvisi — card view di mobile ditambahkan |
| Estimasi timeline | ⭐⭐⭐ → ⭐⭐⭐⭐ | ✅ Direvisi — 22-28 hari (realistis + TDD) |
| Testability & acceptance criteria | ⭐⭐ → ⭐⭐⭐⭐ | ✅ Direvisi — AC ditambahkan, TDD plan lengkap |

### 15.2 Temuan Audit — Isu yang SUDAH Direvisi di v1.3

| # | Temuan | Severity | Revisi yang Diterapkan | Section |
|---|---|---|---|---|
| A1 | Relasi User↔Divisi one-to-many, tidak fleksibel untuk anggota yang merangkap divisi | 🔴 Tinggi | Diubah ke many-to-many via pivot `divisi_user` | §6 |
| A2 | ENUM pada `AdminTask.divisi` — susah di-maintain jika divisi baru | 🔴 Tinggi | Diganti FK ke tabel `Divisi` | §6 |
| A3 | Tidak ada FR reset password | 🔴 Tinggi | Ditambahkan FR-1.4 | §5.1 |
| A4 | Tidak ada Laravel Policy (risiko IDOR) | 🔴 Tinggi | Ditambahkan FR-1.5 + NFR keamanan | §5.1, §7 |
| A5 | Tidak ada FR import data dari Excel | 🔴 Tinggi | Ditambahkan §5.8 (FR-8.1, FR-8.2) | §5.8 |
| A6 | Ambiguitas warna badge status ("abu/merah") | 🟡 Sedang | Diperjelas: abu=Belum Mulai, merah hanya overdue | §5.3 FR-3.2 |
| A7 | Status inventory overlap (Tersedia vs Sudah Kembali) | 🟡 Sedang | Diperjelas makna per status berdasarkan sistem pengadaan | §5.4 FR-4.1 |
| A8 | Tidak ada entitas Event sebagai parent | 🟡 Sedang | Ditambahkan entitas `Event` + FK di task/inventory/konsumsi | §6 |
| A9 | `total_biaya` kontradiksi accessor vs stored | 🟡 Sedang | Diperjelas: stored di DB via observer | §6 |
| A10 | Tidak ada dashboard total anggaran lintas divisi | 🟡 Sedang | Ditambahkan FR-2.6 | §5.2 |
| A11 | Tidak ada bulk update status | 🟡 Sedang | Ditambahkan FR-3.7 | §5.3 |
| A12 | Tidak ada filter inventory by status pengadaan | 🟡 Sedang | Ditambahkan FR-4.4 | §5.4 |
| A13 | Tidak ada inline "last modified by" di tabel | 🟡 Sedang | Ditambahkan FR-7.3 + field `last_modified_by/at` di model | §5.7, §6 |
| A14 | Timeline terlalu agresif (14-18 hari) | 🟡 Sedang | Direvisi ke 22-28 hari termasuk TDD & buffer | §9 |
| A15 | Tidak ada acceptance criteria per FR | 🟡 Sedang | AC ditambahkan di FR kritis (FR-2.1), sisanya didefinisikan via test | §5.2, §14 |
| A16 | Keamanan kurang (session, password, backup) | 🟡 Sedang | Diperkuat di NFR §7 | §7 |
| A17 | Tabel mobile tidak ada strategi | 🟡 Sedang | Card view di breakpoint `sm` ditambahkan di NFR | §7 |
| A18 | Tidak ada metodologi testing | 🟡 Sedang | TDD strategy lengkap ditambahkan | §14 |

### 15.3 Temuan Audit — Catatan yang TIDAK Memerlukan Revisi PRD

| # | Catatan | Alasan Tidak Direvisi |
|---|---|---|
| B1 | PJ tunggal per task (tidak support multi-PJ) | Cukup untuk v1; multi-PJ bisa ditambahkan v1.1 via pivot table `task_assignees` tanpa breaking change |
| B2 | Tidak ada modul RAB (pemasukan vs pengeluaran) | Sudah eksplisit di Non-Goals §2 — SiPanitia bukan sistem keuangan |
| B3 | Tidak ada calendar/timeline view | Nice-to-have untuk v2; data tanggal sudah tersimpan, tinggal tambah view |
| B4 | Tidak ada modul scoring lomba | Beda domain (pelaksanaan lomba vs monitoring kepanitiaan) |
| B5 | Tidak ada offline/PWA support | Over-engineering untuk v1; mitigasi "koneksi tidak stabil" cukup dengan halaman ringan |

### 15.4 Package yang Direkomendasikan (Hasil Audit)

| Kebutuhan | Package | Catatan |
|---|---|---|
| Tabel interaktif Livewire | `rappasoft/laravel-livewire-tables` atau `power-components/livewire-powergrid` | Hemat 3-5 hari development; sudah include filter, sort, export, pagination |
| Export Excel | `maatwebsite/laravel-excel` | Standar industri, support import juga (untuk FR-8.1) |
| Export PDF | `barryvdh/laravel-dompdf` | Ringan, cukup untuk laporan sederhana |
| Import Excel | `maatwebsite/laravel-excel` (fitur import) | Satu package untuk import & export |
| Notifikasi | Laravel built-in `Notification` | Support database + email channel, tidak perlu package tambahan |
| Monitoring | Laravel Pulse | Sudah disebutkan di §13.6 |

### 15.5 Kesesuaian PRD dengan Dokumen Proposal Acara

Cross-reference dengan `17-draft-matang.md` (proposal konsep acara HUT RI):

| Aspek dari Proposal | Status di PRD |
|---|---|
| Struktur sie (BPH, Acara, Perkap, Konsumsi, Humas, Dekdok) | ✅ Ter-cover |
| Checklist kesiapan H-30 s/d H-1 | ✅ Bisa diinput sebagai template AdminTask/EventTask |
| Form peminjaman barang ke warga | ✅ Ter-cover oleh modul Inventory |
| Konsumsi per acara (outbound + tirakatan) | ✅ Ter-cover oleh modul Consumption |
| RAB (pemasukan & pengeluaran lengkap) | ❌ Non-Goal — hanya estimasi biaya per item |
| Timeline/calendar visual | ❌ Data tersimpan, view bisa ditambahkan v2 |
| Daftar kontak darurat | ⚠️ Bisa masuk di halaman Panduan (FR-2.4) yang editable Super Admin |

---

*Dokumen ini adalah PRD (v1.3 — post-audit revision) dan dapat direvisi seiring masukan dari tim panitia.*

*Riwayat versi:*
- *v1.0 — Draft awal*
- *v1.1 — Penambahan detail modul*
- *v1.2 — Strategi deployment & performa, opsi hosting gratis*
- *v1.3 — Hasil audit: revisi model data, 6 FR baru, strategi TDD, perbaikan keamanan & timeline*