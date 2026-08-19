# Feature: Social Feed (Posts)

Status: ✅ Spesifikasi final. Referensi produk: [`product/02-problem-solution-feature-mapping.md`](../../product/02-problem-solution-feature-mapping.md) §B3.

## User Flow

1. Member membuat post (`text`/`image`/`memory`), memilih visibility.
2. Post muncul di feed, urut terbaru, dengan infinite scroll (bukan pagination angka — lebih natural untuk feed).
3. User lain bisa react (like) dan comment.
4. User bisa report post yang dianggap melanggar (masuk `reports`, lihat [`05-privacy-and-moderation.md`](../../product/05-privacy-and-moderation.md)).
5. Author bisa menghapus post sendiri (soft delete).

## Data Model

Tabel `posts`, `comments` (polymorphic), `reactions` (polymorphic), `reports` (polymorphic) — lihat [`03-database.md`](../03-database.md).

## API

`GET/POST/DELETE /api/v1/posts`, `/posts/{id}/comments`, `/posts/{id}/reactions`, `/api/v1/reports` — lihat [`04-api.md`](../04-api.md).

## Edge Case & Validasi

- Post kosong (tanpa body & tanpa media) → ditolak validasi.
- Reaction ganda oleh user yang sama → dicegah oleh unique constraint di `reactions` (lihat `03-database.md`), bukan hanya validasi frontend.
- Post yang di-soft-delete tetap muncul di `audit_logs` jika sebelumnya sempat di-report, untuk keperluan moderasi.
- Comment di post yang sudah dihapus → tidak bisa ditambah komentar baru, tapi komentar lama tetap terlihat di riwayat moderasi (bukan user-facing).

## Permission

Create: semua role login berstatus `active` (`PostPolicy@create`). Delete: hanya author sendiri atau Moderator/Class Admin (`PostPolicy@delete` vs `@moderate`, lihat [`06-authorization.md`](../06-authorization.md)).
