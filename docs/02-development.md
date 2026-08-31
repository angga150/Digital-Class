# Development Guide

Dokumen ini menjawab dua pertanyaan:

1. **"Saya baru clone project ini, sekarang saya harus ngapain?"** → lihat bagian Setup
2. **"Saya dapat task, saya harus mulai dari mana?"** → lihat bagian Workflow

Kalau kamu belum pernah setup project Laravel sebelumnya, tenang — semua command di bawah dijelaskan, bukan cuma dilempar begitu saja.

## 1. Requirements

Install dulu di komputer kamu (kalau belum ada):

- **PHP** (versi 8.3 ke atas)
- **Composer** — package manager untuk PHP (cara install dependency Laravel)
- **Node.js** dan **npm** — dibutuhkan untuk build Tailwind CSS
- **MySQL** — database yang kita pakai
- **Git** — untuk clone & kelola kode

## 2. Clone Repository

```bash
git clone https://github.com/angga150/Digital-Class.git
cd digital-class
```

Source code Laravel-nya ada di dalam folder `src/`, jadi masuk ke situ dulu:

```bash
cd src
```

**Semua command Laravel/Composer/npm di bawah ini dijalankan dari dalam folder `src/`**, bukan dari root repository.

## 3. Install Dependency

```bash
composer install
```

Ini mengunduh semua library PHP yang dibutuhkan Laravel (didaftarkan di `composer.json`).

```bash
npm install
```

Ini mengunduh semua library JavaScript yang dibutuhkan untuk build Tailwind CSS (didaftarkan di `package.json`).

## 4. Setup Environment (`.env`)

Laravel butuh file `.env` untuk menyimpan konfigurasi lokal (database, dsb.) yang berbeda-beda tiap komputer. File ini **tidak** ikut di-commit ke Git (karena isinya bisa berisi password), jadi kamu perlu buat sendiri dari contohnya:

```bash
cp .env.example .env
```

**Di Windows** (kalau `cp` tidak dikenali di Command Prompt), pakai:

```bash
copy .env.example .env
```

Atau kalau pakai Git Bash / WSL, `cp` tetap berfungsi normal.

Setelah itu, generate application key:

```bash
php artisan key:generate
```

Command ini membuat _application encryption key_ — semacam kunci rahasia yang dipakai Laravel untuk mengenkripsi data sensitif (session, cookie, dll). Wajib dijalankan, kalau tidak Laravel akan error saat dibuka.

## 5. Setup Database

1. Buat database baru di MySQL, misalnya namanya `digital_class`.
2. Buka file `.env`, sesuaikan bagian berikut dengan database kamu:

```env
DB_DATABASE=digital_class
DB_USERNAME=root
DB_PASSWORD=
```

Ganti `DB_USERNAME` dan `DB_PASSWORD` sesuai setup MySQL di komputer kamu (kalau pakai XAMPP/Laragon biasanya `root` tanpa password).

3. Jalankan migration (migration adalah "cetak biru" struktur tabel — perintah ini yang benar-benar membuat tabel-tabel di database kamu):

```bash
php artisan migrate --seed
```

Flag `--seed` sekaligus mengisi database dengan data contoh (member dummy, beberapa post/event) — supaya kamu tidak mulai dari database kosong yang membosankan saat develop fitur sosial.

## 6. Menjalankan Project

Project ini butuh **dua terminal berjalan bersamaan** — satu untuk server Laravel, satu untuk build Tailwind CSS secara live.

**Terminal 1** — jalankan server Laravel:

```bash
php artisan serve
```

**Terminal 2** — jalankan Vite (build tool untuk Tailwind/CSS/JS, otomatis update saat kamu edit file styling):

```bash
npm run dev
```

Biarkan dua-duanya tetap berjalan selama kamu develop. Buka `http://localhost:8000` di browser — website seharusnya sudah bisa diakses.

## 7. Struktur Project yang Perlu Dipahami Pemula

```
src/
├── app/
│   ├── Models/       ← definisi data (User, Post, Event, dll.)
│   ├── Livewire/     ← komponen interaktif (logic di balik tampilan)
│   └── Policies/     ← aturan siapa boleh ngapain
├── database/
│   ├── migrations/   ← "cetak biru" struktur tabel
│   └── seeders/      ← data contoh untuk development
├── resources/
│   ├── views/        ← file Blade (template HTML)
│   └── css/          ← file Tailwind CSS
├── routes/           ← daftar URL/halaman yang tersedia
└── tests/            ← automated test
```

Penjelasan singkat tiap bagian:

- **`app/Models`** — tiap file di sini mewakili satu "jenis data", misalnya `Post.php` mewakili tabel `posts`. Di sinilah kamu definisikan relasi antar tabel (misal: satu Post punya banyak Comment).
- **`app/Livewire`** — tiap file di sini adalah satu komponen interaktif, misalnya `CreatePost.php` menangani logic saat user bikin post baru. Livewire component biasanya berpasangan dengan file Blade di `resources/views/livewire/`.
- **`database/migrations`** — setiap kali kamu butuh tabel baru atau kolom baru, kamu buat file migration baru di sini (bukan mengubah tabel manual lewat phpMyAdmin).
- **`database/seeders`** — data contoh/dummy untuk kebutuhan development, supaya tim tidak kerja dengan database kosong.
- **`resources/views`** — file `.blade.php`, ini yang menentukan tampilan HTML. File di `resources/views/livewire/` adalah tampilan untuk komponen Livewire.
- **`resources/css`** — file Tailwind, biasanya kamu jarang perlu edit langsung karena styling dilakukan lewat class di file Blade.
- **`routes`** — file `web.php` di sini menentukan URL apa mengarah ke halaman/komponen apa.
- **`tests`** — automated test, lihat bagian Testing di bawah untuk kapan kamu wajib menulis test.

## 8. "Saya Ingin... Pergi ke..."

Tabel cepat kalau kamu bingung file mana yang harus diedit:

| Saya ingin...                                                       | Pergi ke...                                                                    |
| ------------------------------------------------------------------- | ------------------------------------------------------------------------------ |
| Mengubah/menambah struktur tabel database                           | `src/database/migrations/` (buat file migration baru)                          |
| Mengubah relasi/logic data                                          | `src/app/Models/`                                                              |
| Membuat komponen interaktif baru (misal: form, tombol dengan logic) | `src/app/Livewire/`                                                            |
| Mengubah tampilan/HTML                                              | `src/resources/views/`                                                         |
| Mengubah styling                                                    | Class Tailwind langsung di file Blade (jarang perlu edit `src/resources/css/`) |
| Mengubah aturan siapa boleh ngapain                                 | `src/app/Policies/`                                                            |
| Menambah/mengubah URL halaman                                       | `src/routes/web.php`                                                           |
| Menambah data contoh untuk development                              | `src/database/seeders/`                                                        |
| Menulis automated test                                              | `src/tests/`                                                                   |
| Mengubah konfigurasi Laravel                                        | `src/config/` (jarang perlu diubah manual)                                     |

## 9. How to Create a Feature (Workflow Standar)

Ini alur yang dipakai setiap kali mengerjakan fitur baru — baik kamu fokus backend, frontend, atau dua-duanya:

1. **Baca dokumentasi fitur** di `docs/features/[nama-fitur].md` — pahami user flow, business rules, dan edge case-nya dulu sebelum coding
2. **Buat/ubah migration** kalau fitur butuh tabel/kolom baru
3. **Buat/ubah Model** dan relasinya
4. **Buat Livewire component** untuk logic interaktifnya
5. **Implementasikan business logic** sesuai aturan di dokumentasi fitur
6. **Tambahkan validation** — jangan percaya input mentah dari user
7. **Tambahkan authorization** (Policy) kalau fitur ini punya aturan siapa-boleh-apa
8. **Buat tampilan (Blade)**
9. **Styling dengan Tailwind**
10. **Tangani loading state, empty state, error state** — lihat prinsip UI di [`03-architecture.md`](./03-architecture.md)
11. **Tambahkan test** untuk logic yang berisiko (lihat bagian Testing di bawah)
12. **Test manual** — coba sendiri di browser sebelum PR, coba juga dengan role berbeda (misal: Member dan Class Admin) untuk pastikan permission benar
13. **Commit** dengan pesan yang jelas (lihat [`CONTRIBUTING.md`](../CONTRIBUTING.md))
14. **Push** branch kamu
15. **Buat Pull Request**

Urutan 2→3→4 (migration → model → Livewire) bukan aturan kaku, tapi pola yang disarankan: bangun dari data ke luar, jangan mulai dari tampilan lalu menambal logic belakangan.

## 10. Testing

Kita **tidak** mengejar angka coverage tinggi hanya supaya terlihat profesional. Prioritas: test logic yang **berisiko menyebabkan data salah atau permission bocor** — bukan semua baris kode.

**Wajib ditest:**

- Authentication (login, registrasi lewat join code)
- Authorization (Policy — pastikan Member tidak bisa lakukan aksi Class Admin)
- Birthday visibility (data privat tidak bocor ke yang tidak berhak)
- Poll voting (satu user tidak bisa vote dua kali)
- Post & comment (soft delete, reaction tidak bisa dobel)
- Aturan bisnis penting lain yang disebutkan di masing-masing `docs/features/*.md`

**Tidak wajib ditest detail:**

- Tampilan visual (styling) — dicek manual saat development
- CRUD sederhana tanpa business logic (misal: update bio profil)

Tooling: PHPUnit/Pest bawaan Laravel. Test Livewire component pakai `Livewire::test(...)` bawaan Laravel — tidak perlu browser testing otomatis (Dusk) di MVP, cukup jalankan manual di browser sebelum PR.

```bash
php artisan test
```

## 11. Kalau Ada Masalah Saat Setup

Masalah umum yang sering terjadi:

- **`php artisan serve` error "could not find driver"** → cek ekstensi PHP untuk MySQL (`pdo_mysql`) sudah aktif
- **`npm run dev` tidak update tampilan** → pastikan Terminal 1 (`php artisan serve`) dan Terminal 2 (`npm run dev`) dua-duanya tetap berjalan bersamaan
- **Error "SQLSTATE... Access denied"** → cek ulang `DB_USERNAME`/`DB_PASSWORD` di `.env` sesuai setup MySQL kamu
- **Masih bingung juga?** → tanya di grup tim, jangan diam-diam stuck berjam-jam
