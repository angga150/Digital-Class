# Feature: Polling / Voting

Status: ✅ Spesifikasi final. Referensi produk: [`product/02-problem-solution-feature-mapping.md`](../../product/02-problem-solution-feature-mapping.md) §C1.

## User Flow

1. Class Admin membuat polling (pertanyaan + opsi, anonim atau tidak, deadline, visibilitas hasil).
2. Member memilih satu opsi (MVP: single choice saja — multiple choice ditunda ke P1 untuk menyederhanakan validasi vote-ganda).
3. Sistem mencegah user vote dua kali pada polling yang sama.
4. Polling otomatis ditutup saat `closes_at` lewat (job terjadwal), atau ditutup manual oleh Class Admin.
5. Hasil ditampilkan sesuai `result_visibility`.

## Data Model

Tabel `polls`, `poll_options`, `poll_votes` — lihat [`03-database.md`](../03-database.md). Unique constraint mencegah vote ganda per user per poll.

## API

`GET/POST /api/v1/polls`, `/polls/{id}/vote`, `/polls/{id}/results` — lihat [`04-api.md`](../04-api.md).

## Edge Case & Validasi

- Vote setelah `closes_at` → ditolak di level server (bukan hanya disable tombol di UI, karena request API langsung tetap harus divalidasi).
- Polling anonim (`is_anonymous = true`) → `poll_votes.user_id` tetap disimpan (untuk mencegah vote ganda), tapi **tidak pernah ditampilkan** ke siapa pun termasuk Class Admin di hasil — hanya agregat angka yang tampil.
- Semua opsi 0 vote → tampilkan hasil apa adanya (0%), bukan disembunyikan.

## Permission

Create/close manual: `Class Admin` & `Super Admin` (`PollPolicy@create`). Vote: semua role login berstatus `active` (`PollPolicy@vote`). Lihat hasil admin-only: dibatasi Policy `viewResults`.
