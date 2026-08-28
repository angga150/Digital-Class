# Database Guide

Dokumen ini merangkum struktur basis data, konvensi penamaan, serta relasi antar tabel yang digunakan dalam aplikasi Digital Class Universe.

## 1. Database Technology
- **DBMS:** MySQL / MariaDB
- **Manajemen Skema:** Laravel Migrations & Seeders

## 2. Konvensi Penamaan (Naming Conventions)
- Nama tabel menggunakan bentuk jamak (*plural* snake_case), contoh: `users`, `posts`, `polls`.
- Nama *foreign key* menggunakan format singular_id, contoh: `user_id`, `poll_id`.
- Kolom timestamp standar Laravel (`created_at`, `updated_at`).

## 3. Tabel Utama (Main Tables)

### A. Tabel `users`
Menyimpan data akun mahasiswa dan pengurus kelas.
- `id` (Primary Key)
- `name` (String)
- `email` (String, Unique)
- `password` (String)
- `birth_date` (Date)
- `role` (Enum: `member`, `admin`)
- `timestamps`

### B. Tabel `posts`
Menyimpan pengumuman, cerita, atau kiriman di feed kelas.
- `id` (Primary Key)
- `user_id` (Foreign Key ke `users`)
- `content` (Text)
- `image_path` (String, nullable)
- `timestamps`

### C. Tabel `polls` & `poll_options` & `poll_votes`
Digunakan untuk fitur voting/polling kelas.

### D. Tabel `events`
Menyimpan jadwal kegiatan kelas dan pengingat.

## 4. Cara Menambah / Mengubah Struktur Database
Selalu gunakan migration Laravel agar perubahan struktur database seragam di semua komputer anggota tim:

1. Buat file migration baru:
   ```bash
   php artisan make:migration create_nama_tabel_table
   ```
2. Edit file migration di `src/database/migrations/`.
3. Jalankan migrasi:
   ```bash
   php artisan migrate
   ```
