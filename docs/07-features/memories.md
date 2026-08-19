# Feature: Class Timeline & On This Day (Memories)

Status: ✅ Spesifikasi final. Referensi produk: [`product/02-problem-solution-feature-mapping.md`](../../product/02-problem-solution-feature-mapping.md) §D2 (Timeline, P0) dan §D4 (On This Day, P2).

## User Flow — Class Timeline (P0)

1. Member menambah entri timeline: judul, cerita singkat, tanggal kejadian, foto pendukung.
2. Timeline ditampilkan kronologis (grouped by tahun), bisa dikomentari & di-react.
3. Entri tidak butuh approval sebelum tayang (Community Owned) — moderasi bersifat reaktif (report), bukan preventif (approval), agar tidak menghambat spontanitas dokumentasi.

## User Flow — On This Day (P2, belum diimplementasi di MVP)

Query harian: cari `timeline_entries`/`gallery_media`/`posts` dengan `event_date`/`created_at` di tanggal-bulan yang sama dari tahun-tahun sebelumnya. **Tidak dibangun di MVP** karena data historis belum cukup untuk bernilai (lihat rasional di `product/02-...` §D4) — bagian ini didokumentasikan sebagai referensi struktur query untuk nanti, bukan untuk dikerjakan sekarang.

## Data Model

Tabel `timeline_entries`, `timeline_media` — lihat [`03-database.md`](../03-database.md). Comment & reaction memakai tabel polymorphic yang sama dengan Post/Gallery.

## API

`GET/POST /api/v1/timeline` — lihat [`04-api.md`](../04-api.md). Endpoint On This Day belum didefinisikan — akan ditambahkan saat fitur ini masuk kerja aktif.

## Edge Case & Validasi

- `event_date` di masa depan → ditolak validasi (timeline adalah kejadian yang sudah terjadi, bukan rencana — beda dari `events`).
- Entri tanpa foto → tetap valid (cerita teks saja diperbolehkan).

## Permission

Create: semua role login berstatus `active`. Delete: author sendiri atau Moderator/Class Admin.
