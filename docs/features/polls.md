# Polling

## Status

Planned (belum diimplementasi)

## Tujuan

Ganti voting manual di grup chat (yang sering rawan curang/hilang) dengan sistem yang hasilnya valid dan bisa dipercaya.

## User Flow

```
Class Admin buat polling: pertanyaan + opsi + deadline + anonim/tidak
  ↓
Member pilih satu opsi
  ↓
Sistem cegah user vote dua kali
  ↓
Polling otomatis tutup saat deadline lewat (atau ditutup manual admin)
  ↓
Hasil ditampilkan sesuai pengaturan visibility hasil
```

## Business Rules

- MVP hanya **single choice** (satu opsi per user) — multiple choice ditunda ke pasca-MVP supaya validasi vote-dobel tetap sederhana.
- Vote **tidak bisa diubah** setelah submit di MVP (ditunda ke pasca-MVP kalau dibutuhkan).
- Polling anonim (`is_anonymous = true`): `user_id` tetap disimpan di database (untuk cegah vote dobel), tapi **tidak pernah ditampilkan** ke siapa pun termasuk Class Admin — hanya angka agregat yang tampil.

## Database

Tabel `polls`, `poll_options`, `poll_votes`. Kombinasi poll+user di-unique-kan untuk cegah vote dobel. Lihat [`../04-database.md`](../04-database.md).

## Backend

- `PollPolicy@create` — Class Admin & Super Admin saja.
- `PollPolicy@vote` — semua role login berstatus `active`.
- Vote setelah `closes_at` lewat **ditolak di server**, bukan cuma tombol di-disable di UI (karena request langsung tetap harus divalidasi).

## Frontend

- Form buat polling (Class Admin)
- Tampilan vote: radio button per opsi, submit sekali
- Tampilan hasil: bar chart sederhana persentase per opsi

## Permission

Create/tutup manual: Class Admin & Super Admin. Vote: semua role login berstatus `active`. Lihat hasil admin-only: dibatasi Policy `viewResults` kalau `result_visibility = admin_only`.

## Validation

- Minimal 2 opsi per polling
- `question` wajib diisi
- Vote wajib memilih salah satu opsi yang valid (bukan opsi dari polling lain)

## Edge Cases

- Semua opsi 0 vote → tampilkan hasil apa adanya (0%), bukan disembunyikan
- Polling ditutup manual sebelum deadline → vote yang masuk setelah itu ditolak

## Testing

**Wajib ditest**: satu user benar-benar tidak bisa vote dua kali (test lewat request langsung, bukan cuma lewat UI), dan identitas voter di polling anonim tidak pernah bocor lewat endpoint manapun.

## Related Features

Dashboard, Moderation
