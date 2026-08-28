# Class Gallery

> **Status:** ✅ Spesifikasi final. Referensi produk: [`product/02-problem-solution-feature-mapping.md`](../02-problem-solution-feature-mapping.md) §D1.

Dokumen ini adalah spesifikasi kerja untuk feature **Class Gallery**. Developer yang mendapat task ini seharusnya dapat membaca dokumen ini lalu mulai coding tanpa perlu membaca seluruh dokumentasi repository.

## Cara Membaca

Urutan praktis:

1. Pahami tujuan dan user flow.
2. Periksa business rules dan permission.
3. Lihat data model.
4. Implement backend + Livewire + UI.
5. Tangani validation dan edge cases.
6. Tambahkan test untuk logic kritikal.
7. Update dokumentasi bila ada perubahan keputusan.

## User Flow

1. Member membuat/memilih album (kategori: Class Photo, Event, Campus, Random, Meme, Throwback).
2. Upload foto ke album, opsional caption dan tag member lain.
3. User lain bisa react, comment, report.
4. Album ditampilkan sebagai grid dengan lazy-loading thumbnail (bukan load semua resolusi penuh sekaligus — penting untuk performa di koneksi kampus yang bervariasi).

## Data Model

Tabel `gallery_albums`, `gallery_media`, `media_tags` — lihat [`../04-database.md`](../04-database.md). Strategi penyimpanan file dibahas terpisah di [`../05-deployment.md`](../05-deployment.md) — dokumen ini hanya mengatur data model, bukan driver storage.

## API

`GET /api/v1/albums`, `POST /api/v1/albums/{id}/media` — lihat [`../03-architecture.md`](../03-architecture.md).

## Edge Case & Validasi

- Ukuran file upload dibatasi (nilai pasti ditentukan di `../05-deployment.md` sesuai driver storage yang dipilih) — validasi di server, bukan hanya client.
- Tag member yang bukan anggota kelas (sudah keluar/alumni) → tetap diperbolehkan, karena foto lama tetap relevan secara historis (prinsip *Memory Forever*).
- Upload gambar tanpa album (album belum dibuat) → wajib pilih/buat album dulu, tidak ada foto "lepas" di luar album.

## Permission

Upload: semua role login berstatus `active` (`GalleryMediaPolicy@create`). Moderasi (hapus/report review): Moderator ke atas.

## Testing

Prioritaskan happy path, validation error, permission/visibility, dan edge case yang berisiko mengubah data atau membocorkan data.

## Related Documentation

- [Product](../01-product.md)
- [Development](../02-development.md)
- [Architecture](../03-architecture.md)
- [Database](../04-database.md)
