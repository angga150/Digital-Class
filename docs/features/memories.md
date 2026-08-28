# Class Timeline & On This Day (Memories)

> **Status:** ✅ Spesifikasi final. Referensi produk: [`product/02-problem-solution-feature-mapping.md`](../02-problem-solution-feature-mapping.md) §D2 (Timeline, P0) dan §D4 (On This Day, P2).

Dokumen ini adalah spesifikasi kerja untuk feature **Class Timeline & On This Day (Memories)**. Developer yang mendapat task ini seharusnya dapat membaca dokumen ini lalu mulai coding tanpa perlu membaca seluruh dokumentasi repository.

## Cara Membaca

Urutan praktis:

1. Pahami tujuan dan user flow.
2. Periksa business rules dan permission.
3. Lihat data model.
4. Implement backend + Livewire + UI.
5. Tangani validation dan edge cases.
6. Tambahkan test untuk logic kritikal.
7. Update dokumentasi bila ada perubahan keputusan.

## User Flow — Class Timeline (P0)

1. Member menambah entri timeline: judul, cerita singkat, tanggal kejadian, foto pendukung.
2. Timeline ditampilkan kronologis (grouped by tahun), bisa dikomentari & di-react.
3. Entri tidak butuh approval sebelum tayang (Community Owned) — moderasi bersifat reaktif (report), bukan preventif (approval), agar tidak menghambat spontanitas dokumentasi.

## User Flow — On This Day (P2, belum diimplementasi di MVP)

Query harian: cari `timeline_entries`/`gallery_media`/`posts` dengan `event_date`/`created_at` di tanggal-bulan yang sama dari tahun-tahun sebelumnya. **Tidak dibangun di MVP** karena data historis belum cukup untuk bernilai (lihat rasional di `../01-product.md` §D4) — bagian ini didokumentasikan sebagai referensi struktur query untuk nanti, bukan untuk dikerjakan sekarang.

## Data Model

Tabel `timeline_entries`, `timeline_media` — lihat [`../04-database.md`](../04-database.md). Comment & reaction memakai tabel polymorphic yang sama dengan Post/Gallery.

## API

`GET/POST /api/v1/timeline` — lihat [`../03-architecture.md`](../03-architecture.md). Endpoint On This Day belum didefinisikan — akan ditambahkan saat fitur ini masuk kerja aktif.

## Edge Case & Validasi

- `event_date` di masa depan → ditolak validasi (timeline adalah kejadian yang sudah terjadi, bukan rencana — beda dari `events`).
- Entri tanpa foto → tetap valid (cerita teks saja diperbolehkan).

## Permission

Create: semua role login berstatus `active`. Delete: author sendiri atau Moderator/Class Admin.

## Testing

Prioritaskan happy path, validation error, permission/visibility, dan edge case yang berisiko mengubah data atau membocorkan data.

## Related Documentation

- [Product](../01-product.md)
- [Development](../02-development.md)
- [Architecture](../03-architecture.md)
- [Database](../04-database.md)
