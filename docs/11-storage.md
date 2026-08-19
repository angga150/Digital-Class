# 11 — Storage Strategy

Status: ✅ Final.

## Kebutuhan

Media disimpan oleh: avatar profil (`profiles`), gambar post (`posts`), foto gallery (`gallery_media`), foto timeline (`timeline_media`), gambar event (`events`). Semua sudah pakai kolom `*_path`/`file_path` di skema — dokumen ini menentukan **driver** di baliknya, bukan mengubah skema.

## Local Disk (MVP awal) → Object Storage (begitu traffic riil)

Laravel Filesystem abstraction dipakai sejak awal (`Storage::disk('public')->put(...)`) — ini artinya kode **tidak perlu berubah** saat pindah driver, hanya konfigurasi `.env` yang berubah. Ini alasan utama kenapa keputusan driver aman ditunda tanpa risiko refactor besar.

| Fase | Driver | Alasan |
|---|---|---|
| Development lokal & MVP awal (1 kelas) | `local` (disk `public`, symlink `storage:link`) | Nol biaya, nol setup eksternal, cukup untuk volume 1 kelas kecil |
| Begitu di-deploy ke shared/VPS hosting dengan disk terbatas, atau traffic mulai naik | Object storage (S3-compatible — mis. Cloudflare R2 atau AWS S3 free tier) | Disk VPS murah biasanya kecil (10–20GB); foto/gallery kelas bisa cepat memenuhi itu; object storage juga menyelesaikan masalah backup |

**Trigger pindah ke object storage** (bukan tanggal pasti, tapi kondisi): disk usage VPS >70%, atau deploy pertama ke production sungguhan (bukan lagi localhost). Tidak perlu ditunggu sampai penuh — pindah driver di awal deployment production lebih murah daripada migrasi data setelah penuh.

## Validasi Upload (berlaku semua driver)

| Aturan | Nilai |
|---|---|
| Tipe file diizinkan | `jpg, jpeg, png, webp` (foto), `mp4` dievaluasi terpisah jika video masuk scope — **tidak masuk MVP** |
| Ukuran maksimum per file | 5 MB (cukup untuk foto ponsel terkompresi, mencegah upload foto RAW/mentah yang tidak perlu) |
| Resize otomatis | Ya — generate thumbnail (mis. 400px) untuk grid gallery, simpan juga versi original untuk lihat detail; dilakukan di server (Laravel Intervention Image), bukan mengandalkan client |
| Validasi di server | Wajib — `image` + `mimes:` + `max:` di Form Request Laravel, jangan hanya validasi client-side |

## Struktur Path

```
storage/app/public/
├── avatars/{user_id}/{filename}
├── posts/{class_id}/{filename}
├── gallery/{class_id}/{album_id}/{filename}
├── timeline/{class_id}/{filename}
└── events/{class_id}/{filename}
```

Path selalu disisipi `class_id` (kecuali avatar yang lintas kelas ikut `users`) — konsisten dengan prinsip scoping di [`03-database.md`](./03-database.md), memudahkan audit/hapus data per kelas jika suatu saat dibutuhkan (mis. kelas nonaktif).

## Retensi Data (prinsip *Memory Forever*)

Media yang terkait record soft-deleted (post/gallery/timeline dihapus) **tidak langsung dihapus dari storage** — mengikuti siklus soft-delete Eloquent (lihat `03-database.md`). File fisik baru dihapus permanen lewat scheduled cleanup job yang berjalan lama setelah soft-delete (mis. 90 hari), bukan langsung — cukup waktu untuk pemulihan jika terhapus tidak sengaja.

## Dampak ke Dokumen Lain

Dengan ini final, [`07-features/gallery.md`](./07-features/gallery.md) tidak lagi punya blocker terbuka.
