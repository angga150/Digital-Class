# Moderation

## Status

Planned — **fondasi wajib**, harus jalan bersamaan dengan Social Feed, bukan menyusul setelahnya.

## Tujuan

Menjaga konten kelas tetap aman tanpa mematikan spontanitas Community Owned — lewat mekanisme report yang reaktif (bukan approval yang menghambat semua orang di depan).

## User Flow

```
Member lihat konten bermasalah → klik Report, isi alasan singkat
  ↓
Report masuk ke moderation queue (hanya terlihat Moderator ke atas)
  ↓
Moderator tinjau: dismiss (tidak melanggar) atau hide/hapus (melanggar)
  ↓
Setiap aksi tercatat di audit log
```

## Business Rules

- Konten yang bisa dilaporkan: post, comment, foto gallery, entri timeline, profil.
- Konten yang di-hide **tidak langsung hilang permanen** — bisa direview ulang/di-restore oleh Class Admin/Super Admin.
- **Identitas reporter hanya terlihat oleh Moderator ke atas** — Member biasa tidak tahu siapa yang melaporkan, untuk mencegah balas dendam sosial.
- Report ganda pada konten yang sama ditampilkan teragregasi ("3 laporan"), bukan numpuk sebagai entri terpisah di antrean.
- Setiap aksi moderasi (approve/reject/hide) **wajib** tercatat di audit log — siapa, aksi apa, kapan.

## Database

Tabel `reports` (polymorphic — target: posts/comments/gallery_media/timeline_entries/profiles), `audit_logs`. Lihat [`../04-database.md`](../04-database.md).

## Backend

- `{Model}Policy@moderate` — dicek untuk role Moderator, Class Admin, Super Admin.
- Setiap aksi moderasi menulis satu baris ke `audit_logs`.

## Frontend

- Halaman moderation queue (hanya bisa diakses Moderator ke atas)
- Tombol "Report" kecil tapi mudah ditemukan di tiap konten (post, comment, foto, entri timeline)

## Permission

Report: semua role login. Review queue & aksi moderasi: Moderator, Class Admin, Super Admin. Member biasa **tidak bisa** melihat siapa pelapor.

## Validation

- Report wajib punya alasan (`reason`), tidak boleh kosong

## Edge Cases

- Konten yang sudah di-hide dilaporkan lagi → tetap masuk antrean (barangkali perlu ditinjau ulang keputusannya)
- Report terhadap profil (bukan konten) → memungkinkan pelaporan perilaku user, bukan cuma konten — ditangani lewat mekanisme polymorphic yang sama

## Testing

**Wajib ditest**: Member biasa tidak bisa mengakses moderation queue lewat manipulasi URL/request langsung, dan identitas reporter tidak bocor ke Member biasa lewat endpoint manapun.

## Related Features

Social Feed, Class Gallery, Class Timeline, Member Profile — semua fitur user-generated content bergantung pada fondasi ini.
