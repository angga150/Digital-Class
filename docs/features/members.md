# Member Profile

## Status

Planned (belum diimplementasi)

## Tujuan

Supaya anggota kelas lebih saling kenal — profil yang terasa personal dan santai, bukan seperti CV/LinkedIn.

## User Flow

```
User selesai registrasi (via join code)
  ↓
Diarahkan ke onboarding singkat: isi nickname, avatar, bio, hobi, favorit, fun fact, birthday
  ↓
Achievement "profile_complete" otomatis didapat kalau field wajib terisi
  ↓
User bisa ubah visibility kapan saja dari halaman pengaturan
  ↓
User lain lihat profil dari halaman Members atau klik nama di feed/gallery/timeline
```

## Business Rules

- Field seperti `hobby`/`favorite`/`fun_fact` sengaja bebas/santai — **bukan** form terstruktur formal seperti CV.
- Tidak ada avatar → tampilkan avatar default berupa inisial nama, bukan gambar placeholder generik.
- Tidak ada nickname → fallback tampilkan nama asli.

## Database

Tabel `profiles`, satu-satu dengan `users`. Lihat [`../04-database.md`](../04-database.md).

## Backend

- `ProfilePolicy` — hanya pemilik profil yang bisa update profilnya sendiri.
- Visibility check di level presenter/resource — field seperti `birth_date` difilter sebelum dikirim ke tampilan, bukan disembunyikan cuma di sisi Blade.

## Frontend

- Halaman "Members" — daftar seluruh anggota kelas
- Halaman detail profil per member
- Form edit profil dengan toggle visibility di sebelah tiap field sensitif

## Permission

Update: hanya pemilik profil sendiri. Read: semua role login, field mengikuti `profile_visibility`/`birthday_visibility` masing-masing.

## Validation

- `nickname` maksimal 50 karakter
- `bio`, `fun_fact` maksimal panjang wajar (misal 500 karakter) supaya tidak jadi paragraf tak berujung
- Upload avatar: lihat aturan file upload di [`../03-architecture.md`](../03-architecture.md) §7

## Edge Cases

- Profil belum lengkap sama sekali (user baru) → tampilkan tetap, dengan ajakan lengkapi profil, bukan halaman error

## Testing

**Wajib ditest**: user A tidak bisa mengubah profil user B lewat manipulasi request langsung (bukan cuma disembunyikan di UI).

## Related Features

Dashboard, Birthday System, Social Feed (author info), Achievement System
