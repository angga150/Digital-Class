# Class Gallery

## Status

Planned (belum diimplementasi)

## Tujuan

Satu tempat terpusat untuk foto kelas — supaya tidak tercecer di banyak HP dan hilang setelah lulus (prinsip Memory Forever).

## User Flow

```
Member buat/pilih album (kategori: Class Photo, Event, Campus, Random, Meme, Throwback)
  ↓
Upload foto ke album, isi caption (opsional), tag member lain (opsional)
  ↓
User lain react, comment, atau report
  ↓
Foto ditampilkan sebagai grid dengan thumbnail
```

## Business Rules

- Setiap foto harus masuk ke sebuah album — tidak ada foto "lepas" di luar album.
- Upload dibatasi 5 MB per file, tipe `jpg`/`jpeg`/`png`/`webp` saja (lihat [`../03-architecture.md`](../03-architecture.md) §7).
- Tag member yang sudah keluar/alumni tetap diperbolehkan — foto lama tetap relevan secara historis.
- Foto disimpan di local disk untuk MVP, dengan rencana pindah ke object storage kalau traffic naik (lihat [`../05-deployment.md`](../05-deployment.md) §5).

## Database

Tabel `gallery_albums`, `gallery_media`, `media_tags`. Lihat [`../04-database.md`](../04-database.md).

## Backend

- `GalleryMediaPolicy@create` — semua role login berstatus `active`.
- Generate thumbnail otomatis saat upload (server-side, pakai library image processing) — jangan andalkan resize di sisi browser.
- Validasi tipe & ukuran file **wajib di server**, bukan cuma di form HTML.

## Frontend

- Grid foto (lazy-loading thumbnail, bukan load semua resolusi penuh sekaligus)
- Modal lightbox saat foto diklik, dengan comment & reaction
- Form buat album baru & upload foto

## Permission

Upload: semua role login berstatus `active`. Moderasi (hapus/review report): Moderator ke atas.

## Validation

- File wajib gambar (`image`), maksimal 5 MB
- Caption opsional, maksimal panjang wajar

## Edge Cases

- Upload tanpa pilih album dulu → wajib pilih/buat album, tidak ada jalan pintas upload tanpa album
- Report ganda pada foto yang sama → ditampilkan teragregasi ("3 laporan") di antrean moderator, bukan numpuk sebagai entri terpisah

## Testing

Tidak perlu test kompleks — cukup pastikan validasi ukuran/tipe file benar-benar ditolak di server (bukan cuma dicegah di form).

## Related Features

Class Timeline, Moderation, Member Profile (tagging)
