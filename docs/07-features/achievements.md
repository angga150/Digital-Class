# Feature: Achievement System

Status: ✅ Spesifikasi final (versi minimal). Referensi produk: [`product/02-problem-solution-feature-mapping.md`](../../product/02-problem-solution-feature-mapping.md) §E2.

## User Flow

1. Sistem otomatis memberi achievement saat trigger terpenuhi (mis. `first_login` saat user pertama kali login).
2. User melihat achievement yang sudah didapat di halaman profil sendiri & profil orang lain (jika visibility mengizinkan).
3. Notifikasi in-app muncul saat achievement baru didapat (lihat `07-features/notifications.md`).

## Achievement MVP (3–5 badge dasar)

| Code | Trigger |
|---|---|
| `first_login` | Login pertama kali berhasil |
| `profile_complete` | Semua field wajib profil terisi (bio, hobby, avatar) |
| `historian` | Upload ≥5 entri timeline/gallery (skala diturunkan dari draf awal 20, disesuaikan realita kelas kecil) |

Achievement lain (Social Butterfly, Night Owl, Class Legend, dst.) ditunda ke P1 — butuh event-tracking yang lebih kaya (lihat rasional di `product/02-...` §E2).

## Data Model

Tabel `achievements`, `user_achievements` — lihat [`03-database.md`](../03-database.md). Trigger dieksekusi lewat Laravel Event Listener (mis. `UserLoggedIn` event → listener cek & award `first_login`), bukan dicek manual di tiap controller.

## API

`GET /api/v1/achievements` — lihat [`04-api.md`](../04-api.md).

## Edge Case & Validasi

- Trigger terpenuhi lebih dari sekali (mis. login berkali-kali) → `user_achievements` punya unique constraint (`user_id`, `achievement_id`), award kedua diabaikan diam-diam (tidak error, tidak duplikat).
- Achievement tidak boleh bisa di-revoke oleh user sendiri (hanya Super Admin lewat backend, untuk kasus salah trigger).

## Permission

Read: semua role, tunduk visibility profil. Award: sistem otomatis (bukan aksi manual user), tidak ada endpoint "create achievement for user" yang bisa dipanggil dari sisi client.
