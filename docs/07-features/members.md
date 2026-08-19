# Feature: Member Profile

Status: ✅ Spesifikasi final. Referensi produk: [`product/02-problem-solution-feature-mapping.md`](../../product/02-problem-solution-feature-mapping.md) §B2, [`product/03-target-user-analysis.md`](../../product/03-target-user-analysis.md).

## User Flow

1. Setelah registrasi (via join code, lihat `05-authentication.md`), user diarahkan ke onboarding singkat: isi nickname, avatar, bio, hobi, favorit, fun fact, birthday.
2. `achievement: profile_complete` otomatis didapat saat field wajib terisi.
3. User bisa mengubah `profile_visibility` dan `birthday_visibility` kapan saja dari halaman pengaturan.
4. User lain melihat profil lewat halaman "Members" (daftar seluruh anggota kelas) atau langsung dari feed/gallery/timeline (klik nama).

## Data Model

Tabel `profiles` (1:1 dengan `users`) — lihat [`03-database.md`](../03-database.md). Sengaja terpisah dari `users` (bukan menambah kolom langsung di `users`) agar tabel auth tetap ramping dan bebas dari data yang sering berubah/besar (avatar, bio panjang).

## API

`GET /api/v1/me`, `PATCH /api/v1/profile`, `GET /api/v1/members`, `GET /api/v1/members/{id}` — lihat [`04-api.md`](../04-api.md).

## Edge Case & Validasi

- Nickname kosong → fallback tampilkan `name` asli dari `users`.
- Avatar tidak diupload → tampilkan avatar default generatif (inisial nama), bukan gambar placeholder generik yang terasa impersonal.
- Profil terasa "seperti CV" adalah anti-pattern eksplisit dari produk — field seperti `hobby`/`favorite`/`fun_fact` didesain sebagai array bebas/teks santai, bukan form terstruktur formal.

## Permission

Update: hanya pemilik profil sendiri (`ProfilePolicy@update`). Read: tunduk `profile_visibility` per field, lihat [`06-authorization.md`](../06-authorization.md) §"Privacy Enforcement".
