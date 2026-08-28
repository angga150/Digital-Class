# Moderation System

> **Status:** ✅ Spesifikasi final. Referensi produk: [`product/05-privacy-and-moderation.md`](../05-privacy-and-moderation.md) — ini adalah fondasi P0, bukan fitur tambahan.

Dokumen ini adalah spesifikasi kerja untuk feature **Moderation System**. Developer yang mendapat task ini seharusnya dapat membaca dokumen ini lalu mulai coding tanpa perlu membaca seluruh dokumentasi repository.

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

1. Member melihat konten bermasalah → klik "Report", isi alasan singkat.
2. Report masuk ke `moderation queue` yang hanya terlihat oleh Moderator/Class Admin/Super Admin.
3. Moderator meninjau: `dismiss` (tidak melanggar) atau `hide`/`delete` konten (melanggar).
4. Setiap aksi moderasi dicatat di `audit_logs` — siapa, aksi apa, kapan.

## Data Model

Tabel `reports` (polymorphic, target: posts/comments/gallery_media/timeline_entries/profiles) dan `audit_logs` — lihat [`../04-database.md`](../04-database.md).

## API

`POST /api/v1/reports` — lihat [`../03-architecture.md`](../03-architecture.md). Endpoint queue moderasi (`GET /api/v1/moderation/queue`) belum didetailkan di `../03-architecture.md` karena di MVP moderasi dilakukan lewat halaman web (Livewire), API-nya ditambahkan belakangan jika dibutuhkan (mis. untuk mobile admin).

## Edge Case & Validasi

- Report ganda pada konten yang sama oleh user yang sama → diperbolehkan tercatat lebih dari satu (tidak di-unique-kan), tapi ditampilkan sebagai satu entri teragregasi ("3 laporan") di UI moderator agar tidak membanjiri antrean.
- Konten yang di-hide masih bisa direview ulang (di-restore) oleh Class Admin/Super Admin — tidak permanen kecuali eksplisit dihapus.
- Report terhadap `profiles` (bukan hanya konten) — memungkinkan pelaporan perilaku, bukan cuma konten; ditangani lewat mekanisme sama (polymorphic).

## Permission

Report: semua role login. Review queue & aksi moderasi: Moderator, Class Admin, Super Admin (`{Model}Policy@moderate`, lihat [`../03-architecture.md`](../03-architecture.md)). Member biasa tidak bisa melihat siapa yang melaporkan (identitas reporter hanya terlihat oleh Moderator ke atas, untuk mencegah balas dendam sosial).

## Testing

Prioritaskan happy path, validation error, permission/visibility, dan edge case yang berisiko mengubah data atau membocorkan data.

## Related Documentation

- [Product](../01-product.md)
- [Development](../02-development.md)
- [Architecture](../03-architecture.md)
- [Database](../04-database.md)
