# Deployment

## 1. Prinsip

Deployment MVP harus sederhana.

```text
Local Development
      ↓
Testing
      ↓
Production
```

Staging boleh digunakan jika tim punya kapasitas, tetapi tidak wajib untuk MVP awal.

## 2. Hosting

MVP dapat dijalankan di:

- VPS murah, atau
- PaaS sederhana.

Jangan membangun infrastruktur AWS/GCP multi-service, Kubernetes, atau orchestration kompleks sebelum ada kebutuhan nyata.

## 3. Production Environment

`.env` production tidak pernah masuk Git.

Environment variables dikelola langsung di hosting.

Minimal perhatikan:

- `APP_ENV`
- `APP_KEY`
- `APP_URL`
- database credentials
- mail configuration jika digunakan
- storage configuration

## 4. Deploy Manual MVP

Setelah PR di-merge dan kode diperiksa:

```bash
git pull origin main
composer install --no-dev --optimize-autoloader
npm install
npm run build
php artisan migrate
php artisan optimize
```

Command dapat disesuaikan dengan hosting.

**Jangan menjalankan `migrate:fresh` di production.**

## 5. Database

Migration production:

```bash
php artisan migrate
```

Pastikan migration baru tidak merusak data existing.

Backup database sebelum perubahan berisiko.

## 6. Storage

### MVP awal

Gunakan local storage Laravel:

```text
storage/app/public/
```

Path yang digunakan:

```text
avatars/{user_id}/{filename}
posts/{class_id}/{filename}
gallery/{class_id}/{album_id}/{filename}
timeline/{class_id}/{filename}
events/{class_id}/{filename}
```

Jalankan:

```bash
php artisan storage:link
```

### Validasi Upload

File foto:

```text
jpg
jpeg
png
webp
```

Maksimum:

```text
5 MB per file
```

Video `mp4` tidak termasuk MVP.

Validasi wajib di server. Client-side validation hanya membantu UX.

Resize/thumbnail dapat digunakan untuk gallery agar koneksi kampus tidak harus mengunduh file original setiap saat.

### Kapan pindah object storage?

Pertimbangkan object storage S3-compatible ketika:

- disk VPS >70%, atau
- production sungguhan sudah dimulai dan storage lokal menjadi risiko.

Database tetap hanya menyimpan path.

## 7. Retensi Media

Konten soft-deleted tidak langsung menghapus file fisik.

File dapat dibersihkan oleh scheduled cleanup setelah masa pemulihan, misalnya sekitar 90 hari, jika implementasi cleanup sudah dibuat.

Ini mendukung prinsip **Memory Forever** tanpa membuat storage tumbuh tanpa batas.

## 8. HTTPS

HTTPS wajib di production karena aplikasi menangani:

- login,
- data pribadi,
- birthday,
- upload,
- interaksi kelas.

Gunakan SSL/HTTPS yang tersedia dari hosting, misalnya Let's Encrypt.

## 9. Monitoring Minimal

MVP cukup menggunakan:

- Laravel logs,
- uptime monitoring sederhana.

Tidak perlu APM berbayar sebelum skala membutuhkan.

## 10. CI

GitHub Actions dapat menjalankan satu job test pada Pull Request:

```text
checkout
  ↓
setup PHP
  ↓
composer install
  ↓
php artisan test
```

CI tidak perlu langsung menjadi pipeline deploy penuh.

Auto-deploy dipertimbangkan setelah test dan staging sudah stabil.

## 11. Deployment Checklist

- [ ] `.env` production sudah dikonfigurasi.
- [ ] `APP_KEY` tersedia.
- [ ] Database production tersedia.
- [ ] HTTPS aktif.
- [ ] `php artisan migrate` berhasil.
- [ ] Frontend sudah di-build.
- [ ] `storage:link` tersedia jika memakai local storage.
- [ ] File upload dapat digunakan.
- [ ] Login dan email verification berjalan.
- [ ] Permission diuji.
- [ ] Backup tersedia.
- [ ] Log dapat diperiksa.

## 12. Catatan Keamanan

Jangan:

- commit `.env`,
- menaruh password di source code,
- memakai `migrate:fresh` di production,
- membuka storage private secara publik tanpa authorization,
- menerima file upload tanpa validasi.
