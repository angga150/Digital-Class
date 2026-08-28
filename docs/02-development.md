# Development Guide

## 1. Tujuan Dokumen

Dokumen ini adalah panduan paling praktis untuk developer baru.

Targetnya:

```text
Clone
  ↓
Install
  ↓
Configure
  ↓
Run
  ↓
Understand
  ↓
Find feature
  ↓
Code
  ↓
Test
  ↓
Commit
  ↓
Pull Request
```

## 2. Requirements

Siapkan:

- Git
- PHP versi yang sesuai dengan versi Laravel di `src/composer.json`
- Composer
- Node.js dan npm
- MySQL

Jangan menebak versi dependency. Jika project sudah memiliki `composer.json` atau `package.json`, ikuti requirement yang tercantum di sana.

## 3. Clone Repository

```bash
git clone <URL-REPOSITORY>
cd digital-class-universe
cd src
```

Karena source code Laravel berada di `src/`, command Laravel dijalankan dari folder tersebut.

## 4. Install Dependency

Backend:

```bash
composer install
```

Frontend:

```bash
npm install
```

`composer install` memasang dependency PHP. `npm install` memasang dependency JavaScript/CSS.

## 5. Environment

Buat `.env` dari `.env.example`:

```bash
cp .env.example .env
```

Di Windows, jika `cp` tidak tersedia, copy file secara manual:

```text
.env.example → .env
```

Kemudian generate application key:

```bash
php artisan key:generate
```

Command ini membuat encryption key yang dibutuhkan Laravel.

**Jangan commit `.env`.**

## 6. Database

Buat database MySQL kosong.

Contoh konfigurasi:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=digital_class_universe
DB_USERNAME=root
DB_PASSWORD=
```

Sesuaikan dengan MySQL lokal masing-masing.

Lalu:

```bash
php artisan migrate --seed
```

Migration membuat struktur tabel. Seeder mengisi data contoh agar developer tidak memulai dari database kosong.

Jika project belum memiliki seeder tertentu, gunakan:

```bash
php artisan migrate
```

## 7. Menjalankan Aplikasi

Gunakan dua terminal.

### Terminal 1 — Laravel

```bash
cd src
php artisan serve
```

### Terminal 2 — Vite

```bash
cd src
npm run dev
```

Buka alamat yang ditampilkan Laravel, biasanya:

```text
http://127.0.0.1:8000
```

## 8. Struktur yang Perlu Dipahami

```text
digital-class-universe/
├── README.md
├── CONTRIBUTING.md
├── docs/
├── src/
│   ├── app/
│   │   ├── Models/
│   │   ├── Livewire/
│   │   ├── Policies/
│   │   └── ...
│   ├── database/
│   │   ├── migrations/
│   │   └── seeders/
│   ├── resources/
│   │   ├── views/
│   │   ├── css/
│   │   └── js/
│   ├── routes/
│   └── tests/
└── .github/
```

### Where Should I Edit?

| Saya ingin... | Pergi ke... |
|---|---|
| Mengubah database | `src/database/migrations` |
| Mengisi data contoh | `src/database/seeders` |
| Mengubah Model | `src/app/Models` |
| Membuat/ubah Livewire | `src/app/Livewire` |
| Mengubah Policy | `src/app/Policies` |
| Mengubah tampilan | `src/resources/views` |
| Mengubah CSS | `src/resources/css` |
| Mengubah JS kecil | `src/resources/js` |
| Mengubah route | `src/routes` |
| Membuat test | `src/tests` |
| Mengubah config | `src/config` |

Struktur internal Laravel tetap mengikuti convention Laravel. Jangan membuat `backend/` dan `frontend/` terpisah.

## 9. Cara Kerja Satu Feature

Alur normal:

```text
Browser
  ↓
Route
  ↓
Livewire Component
  ↓
Validation
  ↓
Policy / Authorization
  ↓
Model / Eloquent
  ↓
MySQL
  ↓
Livewire State
  ↓
Blade
  ↓
Tailwind CSS
  ↓
Browser
```

Untuk feature baru:

1. Baca feature specification.
2. Tentukan data yang dibutuhkan.
3. Buat/ubah migration.
4. Buat/ubah Model dan relationship.
5. Tambahkan Policy jika ada permission.
6. Buat Livewire component.
7. Implement logic.
8. Tambahkan validation.
9. Buat UI Blade.
10. Styling dengan Tailwind.
11. Handle loading/empty/error state.
12. Test logic kritikal.
13. Test manual.
14. Commit.
15. Push.
16. Pull Request.

## 10. Backend dan Frontend Bukan Dua Aplikasi

Dalam Laravel + Livewire, satu feature biasanya dikerjakan end-to-end.

Contoh Birthday:

```text
profiles.birth_date
      ↓
Livewire state / query
      ↓
birthday business rule
      ↓
Blade countdown
      ↓
Tailwind UI
```

Backend developer dan frontend developer perlu berkomunikasi mengenai data, state, validation, permission, dan UI.

## 11. Testing

Prioritaskan logic yang berisiko:

- `class_id` scoping,
- visibility enforcement,
- role/Policy,
- vote/reaction unique constraint,
- achievement trigger,
- authentication/authorization,
- business rules penting.

Tidak perlu mengejar 100% coverage.

Tooling MVP:

- PHPUnit/Pest,
- `RefreshDatabase`,
- SQLite in-memory bila kompatibel,
- Livewire component testing.

Dusk/browser testing tidak menjadi prioritas MVP.

## 12. Manual Test

Untuk UI, cek minimal:

- happy path,
- validation error,
- empty state,
- loading state,
- permission berbeda,
- mobile/responsive layout.

Untuk feature yang menyentuh privacy, cek minimal dua role berbeda.

## 13. Security Basics

Sebelum PR:

- [ ] input divalidasi server-side,
- [ ] authorization diperiksa,
- [ ] file upload divalidasi,
- [ ] tidak ada secret di Git,
- [ ] data private tidak tampil ke user yang salah,
- [ ] CSRF tetap aktif,
- [ ] XSS tidak diperkenalkan,
- [ ] query ter-scope `class_id`.

Laravel sudah membantu menyediakan CSRF protection, password hashing, dan mekanisme authentication. Jangan menonaktifkannya tanpa alasan.

## 14. Git Workflow

```bash
git pull origin main
git checkout -b feature/birthday-countdown

# coding

git add .
git commit -m "feat: tambah birthday countdown"
git push origin feature/birthday-countdown
```

Buat Pull Request.

`main` tidak menerima push langsung.

## 15. Pull Request Checklist

- [ ] Feature berjalan.
- [ ] Business rules sesuai dokumentasi.
- [ ] Permission benar.
- [ ] Privacy benar.
- [ ] Test kritikal dijalankan.
- [ ] Dokumentasi diupdate bila ada perubahan.
- [ ] Tidak ada `.env`/secret.
- [ ] PR siap direview.

## 16. Troubleshooting Cepat

### Composer error

```bash
composer install
```

Pastikan versi PHP sesuai dengan requirement `composer.json`.

### Vite tidak jalan

```bash
npm install
npm run dev
```

### Application key belum ada

```bash
php artisan key:generate
```

### Database error

Periksa:

- MySQL hidup,
- nama database,
- username/password,
- host/port,
- `.env` berada di `src/.env`.

### Setelah mengubah migration

Untuk development lokal dan jika data dummy boleh dihapus:

```bash
php artisan migrate:fresh --seed
```

**Jangan gunakan `migrate:fresh` di production.**

## 17. Prinsip Utama

Jika bingung harus memakai pola apa, pilih:

> **Laravel convention terlebih dahulu.**

Jangan membuat repository/service/factory/event system tambahan hanya karena terlihat lebih profesional. Tambahkan abstraction ketika kompleksitas nyata sudah membutuhkannya.
