# Polling / Voting

> **Status:** ✅ Spesifikasi final. Referensi produk: [`product/02-problem-solution-feature-mapping.md`](../02-problem-solution-feature-mapping.md) §C1.

Dokumen ini adalah spesifikasi kerja untuk feature **Polling / Voting**. Developer yang mendapat task ini seharusnya dapat membaca dokumen ini lalu mulai coding tanpa perlu membaca seluruh dokumentasi repository.

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

1. Class Admin membuat polling (pertanyaan + opsi, anonim atau tidak, deadline, visibilitas hasil).
2. Member memilih satu opsi (MVP: single choice saja — multiple choice ditunda ke P1 untuk menyederhanakan validasi vote-ganda).
3. Sistem mencegah user vote dua kali pada polling yang sama.
4. Polling otomatis ditutup saat `closes_at` lewat (job terjadwal), atau ditutup manual oleh Class Admin.
5. Hasil ditampilkan sesuai `result_visibility`.

## Data Model

Tabel `polls`, `poll_options`, `poll_votes` — lihat [`../04-database.md`](../04-database.md). Unique constraint mencegah vote ganda per user per poll.

## API

`GET/POST /api/v1/polls`, `/polls/{id}/vote`, `/polls/{id}/results` — lihat [`../03-architecture.md`](../03-architecture.md).

## Edge Case & Validasi

- Vote setelah `closes_at` → ditolak di level server (bukan hanya disable tombol di UI, karena request API langsung tetap harus divalidasi).
- Polling anonim (`is_anonymous = true`) → `poll_votes.user_id` tetap disimpan (untuk mencegah vote ganda), tapi **tidak pernah ditampilkan** ke siapa pun termasuk Class Admin di hasil — hanya agregat angka yang tampil.
- Semua opsi 0 vote → tampilkan hasil apa adanya (0%), bukan disembunyikan.

## Permission

Create/close manual: `Class Admin` & `Super Admin` (`PollPolicy@create`). Vote: semua role login berstatus `active` (`PollPolicy@vote`). Lihat hasil admin-only: dibatasi Policy `viewResults`.

## Testing

Prioritaskan happy path, validation error, permission/visibility, dan edge case yang berisiko mengubah data atau membocorkan data.

## Related Documentation

- [Product](../01-product.md)
- [Development](../02-development.md)
- [Architecture](../03-architecture.md)
- [Database](../04-database.md)
