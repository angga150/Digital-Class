# Event Countdown

## Status

Planned (belum diimplementasi)

## Tujuan

Supaya event penting (UTS, UAS, wisuda, acara kelas) tidak lagi hilang ditelan chat grup — semua tercatat di satu tempat dengan countdown yang jelas.

## User Flow

```
Class Admin membuat event
  ↓
Isi judul, deskripsi, tanggal+waktu, lokasi, gambar (opsional)
  ↓
Event muncul di Dashboard (countdown) & halaman daftar Event
  ↓
Member lihat detail, tidak bisa edit
  ↓
Tanggal event lewat → status otomatis jadi "completed"
```

## Business Rules

- Hanya Class Admin (dan Super Admin) yang bisa membuat/edit/hapus event.
- Tanggal & waktu disimpan dalam UTC, dikonversi ke waktu lokal saat ditampilkan.
- Countdown menampilkan hitungan **hari** untuk event yang masih >7 hari lagi, dan **jam:menit** untuk event <24 jam — supaya tidak berisik menampilkan detik/menit untuk event yang masih jauh.
- Status event: `upcoming` → `ongoing` → `completed`, atau `cancelled`.

## Database

Tabel `events` — lihat [`../04-database.md`](../04-database.md).

## Backend

- Validasi: `starts_at` tidak boleh di masa lalu saat event dibuat (kecuali admin sengaja membuat entri retroaktif — di luar scope MVP).
- Scheduled task harian mengubah status event yang tanggalnya sudah lewat menjadi `completed`.

## Frontend

- Widget countdown di Dashboard
- Halaman daftar event, bisa difilter berdasarkan status
- Form buat/edit event (hanya muncul untuk Class Admin)

## Permission

Create/update/delete: Class Admin & Super Admin. Read: semua role login, sesuai `visibility`.

## Validation

- `title` wajib diisi
- `starts_at` wajib diisi, format tanggal+waktu valid
- `location` boleh kosong (tampilkan "Lokasi menyusul" kalau kosong, bukan string kosong)

## Edge Cases

- Event tanpa lokasi (online/TBA) → tampilkan pesan yang jelas, bukan kosong begitu saja
- Event dibatalkan (`cancelled`) → tetap tampil di daftar dengan label jelas, tidak dihapus dari database

## Testing

Cukup test manual: pastikan hanya Class Admin yang bisa membuat event (Member coba akses harus ditolak).

## Related Features

Dashboard, Academic Corner
