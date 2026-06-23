# RuangLab — Panduan Menjalankan (Setelah Desain Web Ditambahkan)

Project ini sudah dilengkapi tampilan web (Bootstrap 5) untuk halaman publik
dan dashboard admin, terhubung ke database via Eloquent.

## Apa yang ditambahkan

- **Models**: relasi antar tabel dilengkapi (`MstLaboratorium`, `MstUser`, `MstRole`,
  `MstMataKuliah`, `MstDay`, `TrxReservasi`, `TrxDetailReservasi`, `TrxJadwalKuliah`).
  `MstUser` dijadikan model autentikasi utama (menggantikan `User` bawaan Laravel).
- **Controllers**: `HomeController`, `LaboratoriumController`, `ReservasiController`,
  `AuthController`, dan di namespace `Admin`: `DashboardController`,
  `LaboratoriumController`, `ReservasiController`.
- **Middleware**: `AdminMiddleware` (alias `admin`) untuk membatasi akses dashboard admin.
- **Routes**: `routes/web.php` — halaman publik, auth, reservasi (login wajib),
  dan grup `/admin` (login + role admin wajib).
- **Views**: `resources/views/layouts/app.blade.php` (layout publik) dan
  `resources/views/layouts/admin.blade.php` (layout dashboard dengan sidebar),
  plus seluruh halaman: beranda, daftar/detail lab, login/register, form & riwayat
  reservasi, dashboard admin, CRUD lab, dan approval reservasi.
- **Seeder**: `DatabaseSeeder` membuat role (`admin`, `dosen`, `mahasiswa`) dan
  akun admin default.

## Langkah Setup

1. Install dependency:
   ```bash
   composer install
   npm install   # opsional, hanya jika nanti pakai Vite/Mix
   ```

2. Salin `.env.example` ke `.env` dan sesuaikan koneksi database:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. Jalankan migrasi + seeder:
   ```bash
   php artisan migrate --seed
   ```

4. Buat symlink storage (untuk menampilkan foto laboratorium yang diupload):
   ```bash
   php artisan storage:link
   ```

5. Jalankan server:
   ```bash
   php artisan serve
   ```

## Akun Admin Default (dari seeder)

- Email: `admin@ruanglab.test`
- Password: `password`

**Catatan:** Segera ganti password ini setelah login pertama kali di environment produksi.

## Struktur Halaman

**Publik** (`/`):
- `/` — Landing page
- `/laboratorium` — Daftar laboratorium (bisa dicari)
- `/laboratorium/{id}` — Detail laboratorium + jadwal kuliah rutin
- `/login`, `/register` — Autentikasi
- `/reservasi` — Riwayat reservasi milik user (login wajib)
- `/reservasi/buat` — Form pengajuan reservasi (login wajib)
- `/reservasi/{id}` — Detail status reservasi + kode check-in

**Admin** (`/admin`, login + role admin wajib):
- `/admin` — Dashboard ringkasan statistik
- `/admin/laboratorium` — CRUD laboratorium (tambah/edit/hapus, upload foto)
- `/admin/reservasi` — Daftar semua reservasi (filter status)
- `/admin/reservasi/{id}` — Detail + form approve/reject/ubah status

## Catatan Teknis

- Karena `MstUser`, `MstLaboratorium`, dll menggunakan primary key UUID (string),
  semua model terkait sudah di-set `protected $keyType = 'string'` dan
  `public $incrementing = false`.
- Role pengguna ditentukan lewat tabel `mst_roles` — pastikan ada role bernama
  **"admin"** (case-insensitive) agar `MstUser::isAdmin()` berfungsi.
- Belum ada validasi bentrok jadwal (dua reservasi di jam & lab yang sama) —
  bisa ditambahkan di `ReservasiController@store` sesuai kebutuhan lanjut.
