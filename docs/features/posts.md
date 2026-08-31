# Social Feed (Posts)

## Status

Planned (belum diimplementasi)

## Tujuan

Ruang ekspresi santai untuk anggota kelas — versi ringan dari media sosial, bukan Facebook/Instagram penuh.

## User Flow

```
Member buat post (text/gambar/memory), pilih visibility
  ↓
Post muncul di feed, urut terbaru, infinite scroll
  ↓
User lain react (like) dan comment
  ↓
User bisa report post yang bermasalah
  ↓
Author bisa hapus post sendiri (soft delete)
```

## Business Rules

- Post tidak butuh approval sebelum tayang — moderasi bersifat **reaktif** (lewat report), bukan **preventif** (approval dulu), supaya tidak menghambat spontanitas (prinsip Community Owned).
- Post kosong (tanpa teks & tanpa media) tidak boleh dibuat.
- Satu user hanya bisa react sekali ke post yang sama (dicegah lewat database constraint, bukan cuma validasi tampilan).

## Database

Tabel `posts`, `comments` (polymorphic), `reactions` (polymorphic), `reports` (polymorphic). Lihat [`../04-database.md`](../04-database.md).

## Backend

- `PostPolicy@create` — semua role login berstatus `active` boleh buat post.
- `PostPolicy@delete` — author sendiri, atau Moderator/Class Admin (moderasi).
- Reaction dicegah dobel lewat unique constraint database (`reactable_id`, `reactable_type`, `user_id`, `type`).

## Frontend

- Feed dengan infinite scroll (bukan pagination angka — lebih natural untuk feed)
- Form buat post (modal atau area di atas feed)
- Tombol react & comment di tiap post
- Tombol report (ikon kecil, tidak mencolok tapi mudah ditemukan)

## Permission

Create: semua role login berstatus `active`. Delete: author sendiri atau Moderator/Class Admin. Report: semua role login.

## Validation

- Post harus punya `body` atau `media_path` (tidak boleh dua-duanya kosong)
- Upload gambar mengikuti aturan file upload di [`../03-architecture.md`](../03-architecture.md) §7

## Edge Cases

- Post yang di-soft-delete tetap tercatat di `audit_logs` kalau sebelumnya pernah di-report — untuk keperluan moderasi
- Comment di post yang sudah dihapus → tidak bisa ditambah komentar baru

## Testing

**Wajib ditest**: reaction tidak bisa dobel oleh user yang sama, dan hanya author/moderator yang bisa hapus post orang lain.

## Related Features

Dashboard (aktivitas terbaru), Moderation, Member Profile
