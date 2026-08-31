# Achievement System

## Status

Planned (versi minimal — 3-5 badge dasar)

## Tujuan

Apresiasi ringan atas kontribusi positif di kelas — bukan sistem kompetitif yang mempermalukan siapa pun.

## User Flow

```
User melakukan sesuatu yang jadi trigger (misal: login pertama kali)
  ↓
Sistem otomatis kasih achievement, tidak perlu diajukan manual
  ↓
Notifikasi in-app muncul
  ↓
User lihat koleksi achievement-nya di halaman profil (sendiri & orang lain, sesuai visibility)
```

## Business Rules

- **Achievement MVP (3-5 badge dasar saja):**

| Code | Trigger |
|---|---|
| `first_login` | Login pertama kali berhasil |
| `profile_complete` | Semua field wajib profil terisi |
| `historian` | Upload ≥5 entri timeline/gallery |

- Achievement lain (Social Butterfly, Night Owl, Class Legend, dll.) ditunda ke pasca-MVP — butuh pelacakan aktivitas yang lebih kaya.
- Gamifikasi **tidak boleh kompetitif** — tidak ada leaderboard yang mempermalukan performa rendah, tidak ada peringkat publik "siapa paling sedikit achievement".
- Achievement tidak bisa dihapus/di-revoke oleh user sendiri.

## Database

Tabel `achievements`, `user_achievements` — kombinasi user+achievement di-unique-kan supaya tidak ada award dobel. Lihat [`../04-database.md`](../04-database.md).

## Backend

- Trigger dijalankan lewat Laravel Event Listener (misal: event `UserLoggedIn` → listener cek & award `first_login`) — **bukan** dicek manual di tiap controller, supaya logic-nya tidak tercecer di banyak tempat.
- Kalau trigger terpenuhi lagi (misal login berkali-kali), award kedua diabaikan diam-diam (tidak error, tidak dobel) karena unique constraint.

## Frontend

- Badge ditampilkan di halaman profil (`<x-badge>`)
- Notifikasi in-app saat dapat achievement baru

## Permission

Read: semua role, sesuai visibility profil. Award: otomatis oleh sistem — tidak ada tombol/endpoint untuk user memberi achievement ke diri sendiri atau orang lain.

## Validation

Tidak ada input user langsung (murni trigger otomatis).

## Edge Cases

- User trigger achievement yang sama berkali-kali → hanya di-award sekali, sisanya diabaikan tanpa error

## Testing

**Wajib ditest**: achievement tidak bisa ke-award dobel untuk user yang sama.

## Related Features

Dashboard, Member Profile, Class Gallery/Timeline (trigger `historian`)
