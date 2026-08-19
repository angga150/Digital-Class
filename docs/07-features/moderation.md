# Feature: Moderation System

Status: ✅ Spesifikasi final. Referensi produk: [`product/05-privacy-and-moderation.md`](../../product/05-privacy-and-moderation.md) — ini adalah fondasi P0, bukan fitur tambahan.

## User Flow

1. Member melihat konten bermasalah → klik "Report", isi alasan singkat.
2. Report masuk ke `moderation queue` yang hanya terlihat oleh Moderator/Class Admin/Super Admin.
3. Moderator meninjau: `dismiss` (tidak melanggar) atau `hide`/`delete` konten (melanggar).
4. Setiap aksi moderasi dicatat di `audit_logs` — siapa, aksi apa, kapan.

## Data Model

Tabel `reports` (polymorphic, target: posts/comments/gallery_media/timeline_entries/profiles) dan `audit_logs` — lihat [`03-database.md`](../03-database.md).

## API

`POST /api/v1/reports` — lihat [`04-api.md`](../04-api.md). Endpoint queue moderasi (`GET /api/v1/moderation/queue`) belum didetailkan di `04-api.md` karena di MVP moderasi dilakukan lewat halaman web (Livewire), API-nya ditambahkan belakangan jika dibutuhkan (mis. untuk mobile admin).

## Edge Case & Validasi

- Report ganda pada konten yang sama oleh user yang sama → diperbolehkan tercatat lebih dari satu (tidak di-unique-kan), tapi ditampilkan sebagai satu entri teragregasi ("3 laporan") di UI moderator agar tidak membanjiri antrean.
- Konten yang di-hide masih bisa direview ulang (di-restore) oleh Class Admin/Super Admin — tidak permanen kecuali eksplisit dihapus.
- Report terhadap `profiles` (bukan hanya konten) — memungkinkan pelaporan perilaku, bukan cuma konten; ditangani lewat mekanisme sama (polymorphic).

## Permission

Report: semua role login. Review queue & aksi moderasi: Moderator, Class Admin, Super Admin (`{Model}Policy@moderate`, lihat [`06-authorization.md`](../06-authorization.md)). Member biasa tidak bisa melihat siapa yang melaporkan (identitas reporter hanya terlihat oleh Moderator ke atas, untuk mencegah balas dendam sosial).
