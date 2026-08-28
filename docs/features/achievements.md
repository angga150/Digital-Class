# Achievement System

> **Status:** ✅ Spesifikasi final (versi minimal). Referensi produk: [`product/02-problem-solution-feature-mapping.md`](../02-problem-solution-feature-mapping.md) §E2.

Dokumen ini adalah spesifikasi kerja untuk feature **Achievement System**. Developer yang mendapat task ini seharusnya dapat membaca dokumen ini lalu mulai coding tanpa perlu membaca seluruh dokumentasi repository.

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

1. Sistem otomatis memberi achievement saat trigger terpenuhi (mis. `first_login` saat user pertama kali login).
2. User melihat achievement yang sudah didapat di halaman profil sendiri & profil orang lain (jika visibility mengizinkan).
3. Notifikasi in-app muncul saat achievement baru didapat (lihat `features/notifications.md`).

## Achievement MVP (3–5 badge dasar)

| Code | Trigger |
|---|---|
| `first_login` | Login pertama kali berhasil |
| `profile_complete` | Semua field wajib profil terisi (bio, hobby, avatar) |
| `historian` | Upload ≥5 entri timeline/gallery (skala diturunkan dari draf awal 20, disesuaikan realita kelas kecil) |

Achievement lain (Social Butterfly, Night Owl, Class Legend, dst.) ditunda ke P1 — butuh event-tracking yang lebih kaya (lihat rasional di `../01-product.md` §E2).

## Data Model

Tabel `achievements`, `user_achievements` — lihat [`../04-database.md`](../04-database.md). Trigger dieksekusi lewat Laravel Event Listener (mis. `UserLoggedIn` event → listener cek & award `first_login`), bukan dicek manual di tiap controller.

## API

`GET /api/v1/achievements` — lihat [`../03-architecture.md`](../03-architecture.md).

## Edge Case & Validasi

- Trigger terpenuhi lebih dari sekali (mis. login berkali-kali) → `user_achievements` punya unique constraint (`user_id`, `achievement_id`), award kedua diabaikan diam-diam (tidak error, tidak duplikat).
- Achievement tidak boleh bisa di-revoke oleh user sendiri (hanya Super Admin lewat backend, untuk kasus salah trigger).

## Permission

Read: semua role, tunduk visibility profil. Award: sistem otomatis (bukan aksi manual user), tidak ada endpoint "create achievement for user" yang bisa dipanggil dari sisi client.

## Testing

Prioritaskan happy path, validation error, permission/visibility, dan edge case yang berisiko mengubah data atau membocorkan data.

## Related Documentation

- [Product](../01-product.md)
- [Development](../02-development.md)
- [Architecture](../03-architecture.md)
- [Database](../04-database.md)
