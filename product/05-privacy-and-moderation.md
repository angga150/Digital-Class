# PRIVACY & MODERATION MODEL
## Digital Class Universe

Privacy dan moderasi adalah **fondasi P0**, dibangun sebelum fitur sosial apa pun diluncurkan — bukan ditambal setelahnya. Lihat alasan prioritas di [`02-problem-solution-feature-mapping.md`](./02-problem-solution-feature-mapping.md) bagian Kategori F.

## Data Sensitif

Field-field berikut wajib punya kontrol visibility eksplisit, bukan default terbuka:

- Tanggal lahir (khususnya tahun lahir)
- Email
- Nomor telepon
- Foto profil/pribadi
- Alamat
- Akun media sosial
- Data akademik (nilai, dsb. — jika ada di masa depan)
- Data transaksi kas

## Level Visibility per-Field

| Level | Arti |
|---|---|
| `public` | Bisa dilihat siapa saja, termasuk Guest |
| `class-only` | Hanya anggota kelas yang login |
| `admin-only` | Hanya Class Admin & Super Admin |
| `private` | Hanya pemilik data & Super Admin (untuk keperluan keamanan) |

**Aturan default:** Field sensitif (terutama tanggal lahir & kontak) default ke `class-only`, bukan `public`. User boleh menaikkan visibility, tidak dipaksa menurunkannya.

**Contoh penerapan — Birthday:**
Tanggal lahir bisa `public` / `class-only` / `private`. Jika `private`, sistem tetap boleh menampilkan bahwa hari ini ulang tahun seseorang (untuk keperluan Birthday System) tanpa membuka detail tanggal/tahun lahir ke publik.

## Moderation System

### Target Moderasi
- Post & comment di Social Feed
- Confession
- Gambar di Gallery/Timeline
- Profile (bio, foto) jika dilaporkan

### Mekanisme
- **Report** — member melaporkan konten bermasalah
- **Moderation Queue** — antrean konten yang perlu ditinjau Moderator/Class Admin
- **Hide/Block** — konten disembunyikan tanpa dihapus permanen (bisa direview ulang)
- **Audit Log** — jejak siapa melakukan aksi moderasi apa dan kapan, untuk akuntabilitas dan mencegah tuduhan subjektif

### Status Konten (khusus Confession)
`pending` → `approved` / `rejected` → `archived`

Backend tetap menyimpan `author_id` dan `timestamp` meski identitas tidak ditampilkan ke publik. Moderator dapat mengakses identitas asli **hanya** untuk keperluan keamanan (misal indikasi bullying atau ancaman), bukan untuk konsumsi umum.

## Prinsip Anti-Bullying

- Kategori "penghargaan" di Hall of Fame tidak boleh bersifat mempermalukan; kategori sensitif wajib melalui voting & approval sebelum tayang.
- Confession tanpa moderation system aktif **tidak boleh diluncurkan** — ini alasan Confession diturunkan ke P1 di roadmap, menunggu moderation queue siap.
- Achievement/gamifikasi tidak didesain kompetitif secara langsung (tidak ada leaderboard yang mempermalukan performa rendah).

## Kesiapan Teknis Minimal Sebelum Fitur Sosial Diluncurkan

1. Role & Permission matrix aktif ([`04-roles-and-permissions.md`](./04-roles-and-permissions.md))
2. Privacy visibility per-field aktif di Profile & Birthday
3. Report + Moderation Queue dasar aktif (boleh manual/sederhana di MVP, tapi harus ada)
4. Audit log minimal (siapa, aksi apa, kapan) — bisa berupa log sederhana di awal, tidak perlu sistem canggih
