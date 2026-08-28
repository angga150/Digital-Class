# Member Profile

> **Status:** ✅ Spesifikasi final. Referensi produk: [`product/02-problem-solution-feature-mapping.md`](../02-problem-solution-feature-mapping.md) §B2, [`product/03-target-user-analysis.md`](../03-target-user-analysis.md).

Dokumen ini adalah spesifikasi kerja untuk feature **Member Profile**. Developer yang mendapat task ini seharusnya dapat membaca dokumen ini lalu mulai coding tanpa perlu membaca seluruh dokumentasi repository.

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

1. Setelah registrasi (via join code, lihat `../03-architecture.md`), user diarahkan ke onboarding singkat: isi nickname, avatar, bio, hobi, favorit, fun fact, birthday.
2. `achievement: profile_complete` otomatis didapat saat field wajib terisi.
3. User bisa mengubah `profile_visibility` dan `birthday_visibility` kapan saja dari halaman pengaturan.
4. User lain melihat profil lewat halaman "Members" (daftar seluruh anggota kelas) atau langsung dari feed/gallery/timeline (klik nama).

## Data Model

Tabel `profiles` (1:1 dengan `users`) — lihat [`../04-database.md`](../04-database.md). Sengaja terpisah dari `users` (bukan menambah kolom langsung di `users`) agar tabel auth tetap ramping dan bebas dari data yang sering berubah/besar (avatar, bio panjang).

## API

`GET /api/v1/me`, `PATCH /api/v1/profile`, `GET /api/v1/members`, `GET /api/v1/members/{id}` — lihat [`../03-architecture.md`](../03-architecture.md).

## Edge Case & Validasi

- Nickname kosong → fallback tampilkan `name` asli dari `users`.
- Avatar tidak diupload → tampilkan avatar default generatif (inisial nama), bukan gambar placeholder generik yang terasa impersonal.
- Profil terasa "seperti CV" adalah anti-pattern eksplisit dari produk — field seperti `hobby`/`favorite`/`fun_fact` didesain sebagai array bebas/teks santai, bukan form terstruktur formal.

## Permission

Update: hanya pemilik profil sendiri (`ProfilePolicy@update`). Read: tunduk `profile_visibility` per field, lihat [`../03-architecture.md`](../03-architecture.md) §"Privacy Enforcement".

## Testing

Prioritaskan happy path, validation error, permission/visibility, dan edge case yang berisiko mengubah data atau membocorkan data.

## Related Documentation

- [Product](../01-product.md)
- [Development](../02-development.md)
- [Architecture](../03-architecture.md)
- [Database](../04-database.md)
