# Feature: Class Gallery

Status: ✅ Spesifikasi final. Referensi produk: [`product/02-problem-solution-feature-mapping.md`](../../product/02-problem-solution-feature-mapping.md) §D1.

## User Flow

1. Member membuat/memilih album (kategori: Class Photo, Event, Campus, Random, Meme, Throwback).
2. Upload foto ke album, opsional caption dan tag member lain.
3. User lain bisa react, comment, report.
4. Album ditampilkan sebagai grid dengan lazy-loading thumbnail (bukan load semua resolusi penuh sekaligus — penting untuk performa di koneksi kampus yang bervariasi).

## Data Model

Tabel `gallery_albums`, `gallery_media`, `media_tags` — lihat [`03-database.md`](../03-database.md). Strategi penyimpanan file dibahas terpisah di [`11-storage.md`](../11-storage.md) — dokumen ini hanya mengatur data model, bukan driver storage.

## API

`GET /api/v1/albums`, `POST /api/v1/albums/{id}/media` — lihat [`04-api.md`](../04-api.md).

## Edge Case & Validasi

- Ukuran file upload dibatasi (nilai pasti ditentukan di `11-storage.md` sesuai driver storage yang dipilih) — validasi di server, bukan hanya client.
- Tag member yang bukan anggota kelas (sudah keluar/alumni) → tetap diperbolehkan, karena foto lama tetap relevan secara historis (prinsip *Memory Forever*).
- Upload gambar tanpa album (album belum dibuat) → wajib pilih/buat album dulu, tidak ada foto "lepas" di luar album.

## Permission

Upload: semua role login berstatus `active` (`GalleryMediaPolicy@create`). Moderasi (hapus/report review): Moderator ke atas.
