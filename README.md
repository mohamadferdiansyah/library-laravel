# Library Laravel

Library Laravel adalah aplikasi manajemen perpustakaan sederhana berbasis Laravel 10 dan database MySQL. Aplikasi ini memungkinkan pengelolaan buku, penerbit, anggota, kartu anggota, hingga riwayat peminjaman dengan fitur CRUD lengkap.

## Fitur

- CRUD lengkap untuk semua entitas:
  - Buku (`Book`)
  - Penerbit (`Publisher`)
  - Anggota (`Member`)
  - Kartu Anggota (`MemberCard`)
  - Peminjaman (`Loan`)
- Halaman detail dengan relasi data:
  - Buku menampilkan info penerbit.
  - Anggota menampilkan informasi kartu anggota.
  - Riwayat peminjaman menampilkan nama buku dan anggota.
- Validasi form (contoh: input wajib diisi, format email valid).
- Tampilan sederhana & responsif menggunakan Blade + TailwindCSS.
- Autentikasi admin untuk keamanan akses.
- Filter daftar peminjaman buku yang belum dikembalikan.
- Export data ke PDF dan Excel.
- Fitur pencarian dinamis (live search).

## Teknologi

- Laravel 10
- MySQL
- TailwindCSS (via npm)
- Laravel Excel (untuk export PDF/Excel)

## Cara Menjalankan Proyek

```bash
# Clone repository
git clone https://github.com/username/library-laravel.git

# Masuk ke folder proyek
cd library-laravel

# Install dependency Laravel
composer install

# Salin file .env dan atur database
cp .env.example .env
php artisan key:generate

# Atur koneksi database di .env lalu lakukan migrasi + seeder
php artisan migrate --seed

# Install dependency frontend (TailwindCSS)
npm install

# Jalankan build CSS
npm run dev

# Jalankan server Laravel
php artisan serve
