# Birthday System

## Status

Planned (belum diimplementasi)

## Tujuan

Supaya tidak ada lagi yang lupa ulang tahun teman sekelas — sekaligus jadi salah satu alasan emosional utama orang buka dashboard tiap hari.

## User Flow

```
User mengisi profil
  ↓
Isi tanggal lahir (tahun opsional) + pilih visibility
  ↓
Sistem hitung otomatis siapa yang ulang tahun dalam waktu dekat
  ↓
Tampil di Dashboard & halaman Birthday Calendar
  ↓
Pada hari-H, sistem tandai "hari ini ulang tahun X" (sesuai visibility)
```

## Business Rules

- **Tahun lahir tidak pernah wajib** — user boleh isi tanggal+bulan saja.
- Visibility birthday: `public` / `class_only` (default) / `private`.
- Kalau `private`, sistem tetap boleh menandai "hari ini ulang tahun X" untuk keperluan sapaan sosial, tapi **tidak menampilkan tanggal/tahun lahir eksak** ke user lain.
- Tahun kabisat (29 Februari) dirayakan di 28 Februari pada tahun biasa.

## Database

Field ada di tabel `profiles`: `birth_date`, `birth_year_visible`, `birthday_visibility`. Tidak ada tabel terpisah — birthday adalah atribut profil. Lihat [`../04-database.md`](../04-database.md).

## Backend

- Query "birthday terdekat" = urutkan seluruh member berdasarkan jarak `birth_date` (bulan+tanggal saja) dari hari ini.
- Logic penentuan "hari ini ulang tahun siapa" dijalankan tiap hari (bisa lewat scheduled task Laravel).

## Frontend

- Widget di Dashboard: "🎂 Birthday berikutnya: [nama] — [x] hari lagi"
- Halaman Birthday Calendar: list/kalender semua birthday sesuai visibility yang boleh dilihat viewer
- Form isi tanggal lahir di halaman edit profil, dengan toggle visibility di sebelahnya (bukan tersembunyi di menu terpisah)

## Permission

Setiap user hanya bisa mengubah `birth_date`/visibility miliknya sendiri. Class Admin **tidak bisa** mengubah birthday member lain.

## Validation

- `birth_date` harus tanggal valid, tidak boleh di masa depan
- `birthday_visibility` harus salah satu dari `public`/`class_only`/`private`

## Edge Cases

- User tidak mengisi `birth_date` sama sekali → tidak muncul di widget manapun, tidak error
- Dua orang ulang tahun di hari yang sama → dua-duanya tampil, bukan cuma satu

## Testing

**Wajib ditest**: visibility `private` benar-benar tidak membocorkan tanggal/tahun lahir ke user lain, meski status "hari ini ulang tahun" tetap muncul.

## Related Features

Dashboard, Member Profile
