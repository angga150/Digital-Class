# Development Guide

Selamat datang di panduan teknis Digital Class Universe! Dokumen ini dirancang khusus agar Anda dapat menyiapkan lingkungan pengembangan (*development environment*) dengan cepat dan mudah, bahkan jika Anda baru pertama kali menggunakan Laravel.

## 1. System Requirements

Sebelum mulai, pastikan komputer Anda telah menginstal perangkat lunak berikut:
- **PHP** (Versi 8.2 atau lebih baru)
- **Composer** (PHP Package Manager)
- **Node.js & npm** (Untuk kompilasi asset frontend Tailwind)
- **MySQL / MariaDB** (Database lokal, misal via XAMPP / Laragon)
- **Git** (Version control)

---

## 2. Langkah Instalasi Awal

Ikuti langkah-langkah berikut di terminal / command prompt Anda:

### A. Clone Repository
```bash
git clone https://github.com/username/digital-class-universe.git
cd digital-class-universe
```

> **Catatan Penting:** Seluruh source code aplikasi Laravel berada di dalam folder `src/`. Masuk ke folder tersebut sebelum menjalankan perintah Laravel:
> ```bash
> cd src
> ```

### B. Install Backend Dependencies (Composer)
```bash
composer install
```

### C. Install Frontend Dependencies (npm)
```bash
npm install
```

### D. Konfigurasi Environment (`.env`)
Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
Atau di Windows (Command Prompt):
```cmd
copy .env.example .env
```

Buka file `.env` dengan text editor (VS Code) dan sesuaikan konfigurasi database MySQL Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=digital_class_universe
DB_USERNAME=root
DB_PASSWORD=
```

### E. Generate Application Key
```bash
php artisan key:generate
```
*Penjelasan:* Perintah ini menghasilkan *encryption key* unik yang digunakan Laravel untuk mengenkripsi sesi dan data penting aplikasi agar tetap aman.

### F. Jalankan Database Migration & Seeder
Pastikan server MySQL Anda sudah menyala (misal via XAMPP/Laragon) dan buat database kosong bernama `digital_class_universe`. Lalu jalankan:
```bash
php artisan migrate --seed
```

### G. Menjalankan Aplikasi (Development Server)
Untuk menjalankan aplikasi secara lokal, Anda membutuhkan **dua terminal** yang berjalan bersamaan:

- **Terminal 1 (Backend - Laravel Server):**
  ```bash
  php artisan serve
  ```
  Aplikasi dapat diakses melalui browser di `http://127.0.0.1:8000`.

- **Terminal 2 (Frontend - Vite Asset Compiler):**
  ```bash
  npm run dev
  ```
  Perintah ini mengompilasi file Tailwind CSS secara *real-time* saat Anda melakukan perubahan kode.

---

## 3. Struktur Project di Dalam `src/`

Bagi pemula, kenali fungsi folder utama di dalam `src/`:

```text
src/
├── app/                  # Berisi logika aplikasi (Models, Livewire Components, Controllers, Policies)
├── database/             # Berisi migrasi tabel dan seeder data dummy
├── public/               # File publik yang dapat diakses langsung (gambar, favicon)
├── resources/            # Berisi tampilan Blade (views) dan file CSS Tailwind (css/app.css)
├── routes/               # Definisi rute URL web (`routes/web.php`)
├── storage/              # Berisi file unggahan pengguna dan log aplikasi
└── tests/                # Pengujian otomatis (Feature & Unit tests)
```

---

## 4. "Di Mana Saya Harus Mengedit?" (Cheat Sheet untuk Pemula)

Jika Anda ingin membuat atau mengubah sesuatu, gunakan panduan cepat ini:

| Saya ingin... | Pergi ke direktori / file ini... |
| :--- | :--- |
| Mengubah struktur tabel database | `src/database/migrations/` |
| Menambah / mengubah kolom tabel & relasi | `src/app/Models/` |
| Membuat logika halaman & interaksi UI | `src/app/Livewire/` |
| Mengubah tampilan HTML / antarmuka | `src/resources/views/` |
| Mengubah styling CSS / warna Tailwind | `src/resources/css/app.css` atau konfigurasi Tailwind |
| Menambah URL / halaman baru | `src/routes/web.php` |
| Menambah pengujian (Testing) | `src/tests/` |
| Mengubah konfigurasi aplikasi | `src/config/` |
