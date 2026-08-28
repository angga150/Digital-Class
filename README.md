# Digital Class Universe

Website digital untuk kelas mahasiswa: tempat melihat informasi kelas, berinteraksi, menyimpan foto, dan mengabadikan kenangan bersama.

> **Prinsip utama:** *Fun First, Useful Second, Memory Forever, Privacy First.*

## ✨ Tentang Project

Digital Class Universe dibuat untuk menjadi "rumah digital" satu kelas. Project ini adalah project tugas kelompok mahasiswa, sehingga arsitekturnya sengaja dibuat sederhana, mudah dipelajari, dan tetap sehat untuk dikembangkan.

MVP ditujukan untuk **satu kelas**, tetapi data dan kode tidak boleh dikunci secara hardcode ke satu kelas.

## 🚀 Fitur MVP

- Authentication dan membership kelas
- Class Dashboard
- Academic Corner minimal
- Birthday System
- Event Countdown
- Member Profile
- Social Feed
- Polling / Voting
- Class Gallery
- Class Timeline
- Achievement System
- Moderation dan privacy control
- In-app notification minimal

Fitur P1/P2 seperti Confession, Class Finance, Time Capsule, Hall of Fame, On This Day, dan Random/Fun Features tidak menjadi fokus MVP.

## 🛠 Tech Stack

- **Laravel** — application/backend framework
- **Livewire** — interactive UI
- **Blade** — server-rendered views
- **Tailwind CSS** — styling
- **Alpine.js** — hanya jika interaksi kecil memang membutuhkannya
- **MySQL** — database

Arsitektur: **Monolithic Laravel Application dengan Livewire**.

Tidak ada React, Vue, Inertia, SPA, microservices, Kubernetes, atau API-first architecture di MVP.

## 💻 Quick Start

> Source code Laravel berada di `src/`.

```bash
git clone <URL-REPOSITORY>
cd digital-class-universe
cd src

composer install
npm install

cp .env.example .env
php artisan key:generate

# Buat database MySQL lalu isi DB_* di .env
php artisan migrate --seed

# Terminal 1
php artisan serve

# Terminal 2
npm run dev
```

Windows yang tidak memiliki `cp` dapat menyalin `.env.example` menjadi `.env` secara manual.

Penjelasan setup lengkap dan troubleshooting ada di [`docs/02-development.md`](docs/02-development.md).

## 📚 Dokumentasi

| Dokumen | Untuk apa? |
|---|---|
| [`01-product.md`](docs/01-product.md) | Memahami produk, user, role, privacy, MVP, roadmap |
| [`02-development.md`](docs/02-development.md) | Setup project dan workflow coding harian |
| [`03-architecture.md`](docs/03-architecture.md) | Memahami cara aplikasi disusun |
| [`04-database.md`](docs/04-database.md) | Memahami tabel, relasi, migration, dan aturan data |
| [`05-deployment.md`](docs/05-deployment.md) | Menjalankan aplikasi di production |
| [`features/`](docs/features/) | Spesifikasi masing-masing fitur |

### Kalau mendapat task...

Contoh: **"Kerjakan Birthday Countdown."**

Cukup:

```text
README
  ↓
docs/02-development.md
  ↓
docs/features/birthdays.md
  ↓
coding → test → commit → PR
```

Tidak perlu membaca semua dokumentasi.

## 👥 Team

Repository bersifat **private/team repository**. Anggota tim yang sudah dikenal ditambahkan sebagai collaborator.

Cara bekerja bersama ada di [`CONTRIBUTING.md`](CONTRIBUTING.md).

## 📌 Status Project

**MVP — dalam pengembangan.**

Roadmap utama menggunakan empat status sederhana:

- **Now** — sedang dikerjakan
- **Next** — berikutnya
- **Later** — setelah MVP
- **Ideas** — eksperimen masa depan

## Prinsip Dokumentasi

> **Professional ≠ Complicated.**  
> **Simple ≠ Unprofessional.**

Dokumentasi ini dibuat supaya developer dapat menemukan jawaban dan tahu langkah berikutnya tanpa membaca puluhan file.
