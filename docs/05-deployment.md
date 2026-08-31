# Deployment

Dokumen ini menjelaskan cara project ini di-deploy — tetap sederhana, tidak ada infrastruktur kompleks yang tidak dibutuhkan di skala project ini.

## 1. Alur Umum

```
Local Development (laptop masing-masing)
  ↓
Testing (php artisan test, test manual di browser)
  ↓
Production (server yang diakses kelas sungguhan)
```

Tidak ada staging environment formal wajib di MVP — kalau tim punya waktu, boleh ditambahkan, tapi tidak wajib untuk mulai deploy.

## 2. Tempat Hosting

Pilih salah satu, sesuai kenyamanan tim:

| Opsi | Kapan cocok |
|---|---|
| VPS murah (misalnya lewat panel seperti Laravel Forge/Ploi, atau setup manual) | Tim mau belajar sedikit server admin, ingin kontrol penuh |
| PaaS (Platform-as-a-Service, misalnya Railway atau Render) | Tim mau deploy cepat tanpa urus server sendiri |

Dua-duanya sama-sama valid untuk Laravel biasa — tidak ada yang "lebih benar", pilih yang tim paling nyaman jalankan.

## 3. Environment Variables di Production

`.env` production **tidak pernah** ikut di-commit ke Git. Diisi manual langsung di server (lewat SSH) atau lewat dashboard environment variable di PaaS. Pastikan minimal ini diisi benar:

```env
APP_ENV=production
APP_DEBUG=false
DB_DATABASE=...
DB_USERNAME=...
DB_PASSWORD=...
```

`APP_DEBUG=false` **wajib** di production — kalau dibiarkan `true`, error Laravel akan menampilkan detail kode/struktur database ke publik.

## 4. Database

1. Buat database MySQL di server production (bukan yang sama dengan lokal)
2. Set kredensialnya di `.env` production
3. Jalankan migration (**bukan** `migrate:fresh`, karena itu menghapus semua data yang sudah ada):

```bash
php artisan migrate
```

4. Backup database dilakukan **harian** — penting khusus untuk project ini karena data yang hilang (foto, timeline, post) berarti kenangan kelas yang hilang permanen, bukan sekadar downtime teknis. Cara backup mengikuti fasilitas yang disediakan hosting (kebanyakan VPS panel dan PaaS punya fitur backup otomatis).

## 5. Storage (Foto & Media)

MVP menyimpan foto (avatar, gallery, timeline) di **local disk** server — cukup untuk kebutuhan satu kelas kecil di awal. Kalau nanti disk server mulai penuh (>70% terpakai) atau traffic mulai naik, pindah ke object storage (misalnya Cloudflare R2 atau AWS S3) — tinggal ganti konfigurasi `.env`, kode tidak perlu diubah karena kita pakai Laravel Filesystem (bukan path manual).

Sebelum deploy pertama kali, jalankan:

```bash
php artisan storage:link
```

Supaya folder `storage/app/public` bisa diakses lewat browser.

## 6. Build Frontend

Sebelum deploy, build asset Tailwind/CSS untuk production (bukan mode development):

```bash
npm run build
```

Ini beda dari `npm run dev` yang dipakai saat development — `build` menghasilkan file yang sudah dioptimasi (diperkecil ukurannya) untuk production.

## 7. Cache

Setelah deploy kode baru, jalankan ini supaya Laravel lebih cepat (cache config, route, view):

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

**Penting:** kalau kamu habis mengubah `.env`, jalankan `php artisan config:clear` dulu sebelum `config:cache` lagi — kalau tidak, perubahan `.env` tidak akan kepakai.

## 8. Queue

MVP **tidak** memakai queue worker terpisah — semua proses (kirim notifikasi in-app, dsb.) dijalankan langsung (synchronous), bukan di background. Ini cukup untuk skala satu kelas. Queue baru dipertimbangkan kalau ada proses berat yang bikin user menunggu lama (misalnya proses gambar dalam jumlah besar).

## 9. Deployment Checklist

```
[ ] .env production sudah diisi benar, APP_DEBUG=false
[ ] Database sudah dibuat & migration sudah dijalankan (bukan migrate:fresh)
[ ] php artisan storage:link sudah dijalankan
[ ] npm run build sudah dijalankan (bukan npm run dev)
[ ] Cache config/route/view sudah di-generate ulang
[ ] HTTPS aktif (SSL) — wajib karena ada form login & data pribadi
[ ] Backup database sudah aktif
```

## 10. Proses Deploy (Manual di MVP)

Deploy dilakukan manual setelah PR di-merge ke `main` dan sudah dicek — bukan auto-deploy otomatis begitu merge. Alasannya: tim kecil, frekuensi deploy tidak tinggi, dan auto-deploy tanpa staging environment berisiko fitur yang belum matang langsung live ke kelas sungguhan.

Langkah umum di server:

```bash
git pull origin main
composer install --no-dev
npm install && npm run build
php artisan migrate
php artisan config:cache
```

## 11. Monitoring

Cukup pakai layanan uptime check gratis (misalnya UptimeRobot) untuk tahu kalau situs down — tidak perlu tools monitoring berbayar/kompleks di skala project ini.
