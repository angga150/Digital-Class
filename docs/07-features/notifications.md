# Feature: In-App Notification

Status: ✅ Spesifikasi final (versi minimal). Referensi produk: [`product/02-problem-solution-feature-mapping.md`](../../product/02-problem-solution-feature-mapping.md) §7.16 (dari prompt engineering).

## Prinsip: In-App Only di MVP

Sesuai arahan produk ("untuk MVP prioritaskan in-app notification, jangan membuat terlalu banyak channel sejak awal"), MVP **tidak** mengirim email/push notification — hanya bell icon + daftar notifikasi in-app. Email/push dievaluasi lagi di P1+ (lihat juga *Anti-Feature* di `product/06-...` soal menghindari infrastruktur notifikasi kompleks sejak awal).

## User Flow

1. Trigger terjadi (komentar baru di post milik user, achievement didapat, poll yang dibuat user akan segera ditutup, dsb.)
2. Notifikasi masuk ke tabel notifikasi user, badge counter bertambah.
3. User membuka dropdown notifikasi, klik → ditandai `read`, diarahkan ke konten terkait.

## Data Model

Memakai [Laravel Notifications bawaan](https://laravel.com/docs/notifications) dengan `database` channel (tabel `notifications` standar Laravel — `notifiable_type`, `notifiable_id`, `type`, `data` JSON, `read_at`). Tidak perlu tabel custom, cukup notification classes per jenis event (`NewCommentNotification`, `AchievementAwardedNotification`, dst.)

## API

Tidak dirinci terpisah di `04-api.md` — mengikuti pola standar Laravel Notification (`/api/v1/notifications`, `/notifications/{id}/read`) yang ditambahkan saat implementasi, karena strukturnya sudah standar dan tidak butuh desain khusus.

## Edge Case & Validasi

- Notifikasi untuk konten yang sudah dihapus (mis. post yang di-soft-delete) → tetap tampil di riwayat, tapi link mengarah ke halaman "konten tidak lagi tersedia", bukan error 404 mentah.
- Volume tinggi (mis. post viral dengan banyak komentar) → tidak ada throttling khusus di MVP; dipertimbangkan lagi jika benar-benar jadi masalah nyata.

## Permission

Setiap user hanya melihat & mengelola notifikasi miliknya sendiri — tidak ada akses lintas user, termasuk Class Admin.
