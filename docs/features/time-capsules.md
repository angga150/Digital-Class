# Time Capsule

## Status

Later (sengaja ditunda — bukan menunggu dependensi teknis, murni keputusan prioritas)

## Tujuan

Mahasiswa menulis pesan untuk dirinya sendiri di masa depan (misal: "Semoga saat wisuda nanti aku sudah jadi software engineer"), dibuka otomatis saat tanggal yang ditentukan tiba.

## Kenapa Ditunda

Fitur ini bagus secara emosional, tapi tidak kritikal di hari-hari awal — lebih cocok diluncurkan setelah fitur harian (feed, gallery, timeline) sudah dipakai aktif oleh kelas. Belum ada tabel untuk fitur ini di database saat ini (lihat [`../04-database.md`](../04-database.md)) — akan ditambahkan lewat migration baru saat fitur ini mulai dikerjakan.

## Rancangan Awal (referensi saat dikerjakan nanti)

### User Flow (draf)

```
User tulis pesan untuk masa depan
  ↓
Tentukan tanggal unlock
  ↓
Pesan tersimpan, tidak bisa dibaca siapa pun (termasuk penulisnya) sebelum tanggal unlock
  ↓
Tanggal unlock tiba → pesan bisa dibuka
```

### Business Rules (draf)

- Pesan **tidak boleh bisa dibuka** (dibaca isinya) sebelum `unlock_at` — validasi ini wajib di level server/query, bukan cuma disembunyikan di UI.
- `unlock_at` disimpan UTC, dikonversi ke waktu lokal saat ditampilkan (pola sama dengan `events.starts_at`).

### Data yang dibutuhkan (draf)

`author_id`, `message`, `created_at`, `unlock_at`, `visibility`, `status`

## Yang Harus Diisi Saat Mulai Dikerjakan

- [ ] User flow final
- [ ] Migration & Model
- [ ] Livewire component
- [ ] Edge case (misalnya: apa yang terjadi kalau user keluar dari kelas sebelum `unlock_at` tiba)
- [ ] Permission & validasi

## Related Features

Class Timeline (kesamaan tema kenangan), Member Profile
