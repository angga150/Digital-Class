# Deployment Guide

Panduan praktis untuk merilis (deploy) aplikasi Digital Class Universe ke server produksi (hosting / VPS).

## 1. Alur Rilis
Local Development (Testing) ──> Git Repository ──> Production Server

## 2. Environment Variables (.env) di Production
Pastikan pada server produksi file `.env` diset dengan benar:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://namadomainanda.com
```

## 3. Deployment Checklist
Sebelum aplikasi dibuka untuk seluruh kelas di server production, lakukan pengecekan berikut:
- [ ] Menjalankan `composer install --optimize-autoloader --no-dev`
- [ ] Menjalankan `npm run build` untuk mengompilasi asset frontend production
- [ ] Menjalankan `php artisan config:cache`
- [ ] Menjalankan `php artisan route:cache`
- [ ] Menjalankan `php artisan migrate --force`
- [ ] Memastikan hak akses folder `storage/` dan `bootstrap/cache/` dapat ditulis oleh web server.
