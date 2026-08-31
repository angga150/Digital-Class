# Digital Class Universe

Website digital untuk kelas mahasiswa — pusat informasi, interaksi, identitas, keseruan, dan arsip kenangan kelas dari hari pertama kuliah sampai setelah lulus.

## ✨ About

Bukan sekadar website kelas formal (jadwal, pengumuman) dan bukan media sosial penuh. Digital Class Universe adalah tempat berkumpul digital kelas — kombinasi ringan dari Class Hub, Social Space, dan Digital Scrapbook. Dibuat sebagai project tugas kelompok, tapi diperlakukan sebagai real software project: rapi, terdokumentasi, dan bisa dikembangkan lanjut.

## 🚀 Features

- **Class Dashboard** — info penting kelas dalam satu layar
- **Birthday System** — countdown ulang tahun teman sekelas
- **Event Countdown** — UTS, UAS, wisuda, acara kelas
- **Member Profile** — profil santai, bukan CV
- **Social Feed** — post, komentar, reaksi
- **Polling** — voting dengan hasil yang valid (satu user, satu vote)
- **Class Gallery** — arsip foto kelas
- **Class Timeline** — perjalanan kelas dari semester ke semester
- **Achievement** — badge ringan untuk kontribusi positif
- **Moderation** — report & moderasi konten yang aman

Daftar lengkap & prioritas fitur: [`docs/01-product.md`](./docs/01-product.md)

## 🛠 Tech Stack

- **Laravel** — backend & application logic
- **Livewire** — UI interaktif
- **Tailwind CSS** — styling
- **MySQL** — database

Detail arsitektur: [`docs/03-architecture.md`](./docs/03-architecture.md)

## 💻 Quick Start

```bash
git clone https://github.com/angga150/Digital-Class.git
cd digital-class/src
composer install && npm install
cp .env.example .env
php artisan key:generate
# sesuaikan koneksi database di .env, lalu:
php artisan migrate --seed
```

Jalankan di dua terminal:

```bash
php artisan serve   # terminal 1
npm run dev          # terminal 2
```

Buka `http://localhost:8000`. Panduan lengkap & troubleshooting: [`docs/02-development.md`](./docs/02-development.md)

## 📚 Documentation

| Dokumen                                                | Isi                                                            |
| ------------------------------------------------------ | -------------------------------------------------------------- |
| [`docs/01-product.md`](./docs/01-product.md)           | Visi, masalah, solusi, fitur, roles, privacy, roadmap          |
| [`docs/02-development.md`](./docs/02-development.md)   | Setup project & workflow mengerjakan fitur                     |
| [`docs/03-architecture.md`](./docs/03-architecture.md) | Stack, cara kerja sistem, security basics                      |
| [`docs/04-database.md`](./docs/04-database.md)         | Skema database & relasi antar tabel                            |
| [`docs/05-deployment.md`](./docs/05-deployment.md)     | Cara deploy ke production                                      |
| [`docs/features/`](./docs/features/)                   | Spesifikasi tiap fitur (baca sebelum mengerjakan task terkait) |

## 👥 Team

Diisi oleh anggota tim project.

## 📌 Extension VS Code disarankan diinstall

| Extension                                    | Author           |
| -------------------------------------------- | ---------------- |
| 🟢 Mobile Preview - Phone & Tablet Simulator | by Lirobi        |
| 🟢 Tailwind CSS IntelliSense                 | by Tailwind Labs |
| 🟢 Markdown Preview Mermaid Support          | by Matt Bierner  |

## License

[MIT](./LICENSE)
