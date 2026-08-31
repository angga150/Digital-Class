# Class Timeline & Memories

## Status

Class Timeline: Planned (belum diimplementasi)
On This Day: Idea (belum dijadwalkan — lihat alasan di bawah)

## Tujuan

**Class Timeline** — arsip perjalanan kelas dari semester ke semester, disusun kronologis. Ini salah satu fitur pembeda utama produk ini dari website kelas biasa.

**On This Day** — nanti akan menampilkan kembali kenangan lama secara otomatis di tanggal yang sama tahun sebelumnya. **Belum dikerjakan di MVP** karena nilainya baru terasa setelah ada data historis minimal 1 tahun — dikerjakan sekarang hanya akan jadi fitur kosong tanpa isi.

## User Flow (Class Timeline)

```
Member tambah entri timeline: judul, cerita singkat, tanggal kejadian, foto pendukung
  ↓
Entri langsung tayang (tanpa approval dulu)
  ↓
Ditampilkan kronologis, dikelompokkan per tahun
  ↓
User lain bisa comment & react
```

## Business Rules

- Entri timeline **tidak butuh approval** sebelum tayang — sama seperti Social Feed, moderasi bersifat reaktif (report), bukan preventif.
- `event_date` harus tanggal yang **sudah terjadi**, tidak boleh di masa depan (beda dari `events` yang memang untuk rencana ke depan).
- Entri boleh tanpa foto (cerita teks saja tetap valid).

## Database

Tabel `timeline_entries`, `timeline_media`. Comment & reaction pakai tabel polymorphic yang sama dengan Social Feed. Lihat [`../04-database.md`](../04-database.md).

## Backend

- `TimelineEntryPolicy@create` — semua role login berstatus `active`.
- Delete: author sendiri atau Moderator/Class Admin.

## Frontend

- Tampilan vertical timeline, dikelompokkan per tahun
- Form tambah entri: judul, cerita, tanggal, upload foto (opsional)

## Permission

Create: semua role login berstatus `active`. Delete: author sendiri atau Moderator/Class Admin.

## Validation

- `event_date` wajib diisi, tidak boleh di masa depan
- `title` wajib diisi

## Edge Cases

- Dua entri di tanggal yang sama → dua-duanya tampil, diurutkan berdasarkan waktu dibuat

## Testing

Tidak perlu test kompleks di MVP — cukup pastikan `event_date` di masa depan ditolak validasi.

## Related Features

Class Gallery, Social Feed, Moderation

---

## Catatan untuk On This Day (referensi saat dikerjakan nanti)

Query dasarnya: cari `timeline_entries`/`gallery_media`/`posts` dengan tanggal (bulan+hari) yang sama dengan hari ini, dari tahun-tahun sebelumnya. Baru masuk roadmap kalau data historis sudah cukup — lihat [`../01-product.md`](../01-product.md) §10.
