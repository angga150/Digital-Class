# Social Feed (Posts)

> **Status:** ✅ Spesifikasi final. Referensi produk: [`product/02-problem-solution-feature-mapping.md`](../02-problem-solution-feature-mapping.md) §B3.

Dokumen ini adalah spesifikasi kerja untuk feature **Social Feed (Posts)**. Developer yang mendapat task ini seharusnya dapat membaca dokumen ini lalu mulai coding tanpa perlu membaca seluruh dokumentasi repository.

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

1. Member membuat post (`text`/`image`/`memory`), memilih visibility.
2. Post muncul di feed, urut terbaru, dengan infinite scroll (bukan pagination angka — lebih natural untuk feed).
3. User lain bisa react (like) dan comment.
4. User bisa report post yang dianggap melanggar (masuk `reports`, lihat [`05-privacy-and-moderation.md`](../05-privacy-and-moderation.md)).
5. Author bisa menghapus post sendiri (soft delete).

## Data Model

Tabel `posts`, `comments` (polymorphic), `reactions` (polymorphic), `reports` (polymorphic) — lihat [`../04-database.md`](../04-database.md).

## API

`GET/POST/DELETE /api/v1/posts`, `/posts/{id}/comments`, `/posts/{id}/reactions`, `/api/v1/reports` — lihat [`../03-architecture.md`](../03-architecture.md).

## Edge Case & Validasi

- Post kosong (tanpa body & tanpa media) → ditolak validasi.
- Reaction ganda oleh user yang sama → dicegah oleh unique constraint di `reactions` (lihat `../04-database.md`), bukan hanya validasi frontend.
- Post yang di-soft-delete tetap muncul di `audit_logs` jika sebelumnya sempat di-report, untuk keperluan moderasi.
- Comment di post yang sudah dihapus → tidak bisa ditambah komentar baru, tapi komentar lama tetap terlihat di riwayat moderasi (bukan user-facing).

## Permission

Create: semua role login berstatus `active` (`PostPolicy@create`). Delete: hanya author sendiri atau Moderator/Class Admin (`PostPolicy@delete` vs `@moderate`, lihat [`../03-architecture.md`](../03-architecture.md)).

## Testing

Prioritaskan happy path, validation error, permission/visibility, dan edge case yang berisiko mengubah data atau membocorkan data.

## Related Documentation

- [Product](../01-product.md)
- [Development](../02-development.md)
- [Architecture](../03-architecture.md)
- [Database](../04-database.md)
