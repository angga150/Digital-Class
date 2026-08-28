# In-App Notification

> **Status:** ✅ Spesifikasi final (versi minimal). Referensi produk: [`product/02-problem-solution-feature-mapping.md`](../02-problem-solution-feature-mapping.md) §7.16 (dari prompt engineering).

Dokumen ini adalah spesifikasi kerja untuk feature **In-App Notification**. Developer yang mendapat task ini seharusnya dapat membaca dokumen ini lalu mulai coding tanpa perlu membaca seluruh dokumentasi repository.

## Cara Membaca

Urutan praktis:

1. Pahami tujuan dan user flow.
2. Periksa business rules dan permission.
3. Lihat data model.
4. Implement backend + Livewire + UI.
5. Tangani validation dan edge cases.
6. Tambahkan test untuk logic kritikal.
7. Update dokumentasi bila ada perubahan keputusan.

## Prinsip: In-App Only di MVP

Sesuai arahan produk ("untuk MVP prioritaskan in-app notification, jangan membuat terlalu banyak channel sejak awal"), MVP **tidak** mengirim email/push notification — hanya bell icon + daftar notifikasi in-app. Email/push dievaluasi lagi di P1+ (lihat juga *Anti-Feature* di `../01-product.md` soal menghindari infrastruktur notifikasi kompleks sejak awal).

## User Flow

1. Trigger terjadi (komentar baru di post milik user, achievement didapat, poll yang dibuat user akan segera ditutup, dsb.)
2. Notifikasi masuk ke tabel notifikasi user, badge counter bertambah.
3. User membuka dropdown notifikasi, klik → ditandai `read`, diarahkan ke konten terkait.

## Data Model

Memakai [Laravel Notifications bawaan](https://laravel.com/docs/notifications) dengan `database` channel (tabel `notifications` standar Laravel — `notifiable_type`, `notifiable_id`, `type`, `data` JSON, `read_at`). Tidak perlu tabel custom, cukup notification classes per jenis event (`NewCommentNotification`, `AchievementAwardedNotification`, dst.)

## API

Tidak dirinci terpisah di `../03-architecture.md` — mengikuti pola standar Laravel Notification (`/api/v1/notifications`, `/notifications/{id}/read`) yang ditambahkan saat implementasi, karena strukturnya sudah standar dan tidak butuh desain khusus.

## Edge Case & Validasi

- Notifikasi untuk konten yang sudah dihapus (mis. post yang di-soft-delete) → tetap tampil di riwayat, tapi link mengarah ke halaman "konten tidak lagi tersedia", bukan error 404 mentah.
- Volume tinggi (mis. post viral dengan banyak komentar) → tidak ada throttling khusus di MVP; dipertimbangkan lagi jika benar-benar jadi masalah nyata.

## Permission

Setiap user hanya melihat & mengelola notifikasi miliknya sendiri — tidak ada akses lintas user, termasuk Class Admin.

## Testing

Prioritaskan happy path, validation error, permission/visibility, dan edge case yang berisiko mengubah data atau membocorkan data.

## Related Documentation

- [Product](../01-product.md)
- [Development](../02-development.md)
- [Architecture](../03-architecture.md)
- [Database](../04-database.md)
