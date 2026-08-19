# 10 — Security

Status: ✅ Final untuk MVP.

## Lapisan Keamanan yang Sudah Ada (dari dokumen lain, dirangkum di sini)

| Lapisan | Sudah dibahas di |
|---|---|
| Password hashing, CSRF, rate limit login, verifikasi email | [`05-authentication.md`](./05-authentication.md) |
| Role & permission (Policy/Gate) | [`06-authorization.md`](./06-authorization.md) |
| Field-level visibility (privacy) | [`06-authorization.md`](./06-authorization.md) §"Privacy Enforcement" |
| `class_id` scoping (mencegah akses lintas kelas) | [`03-database.md`](./03-database.md), [`04-api.md`](./04-api.md) |
| Validasi upload file | [`11-storage.md`](./11-storage.md) |

Dokumen ini menambahkan lapisan yang belum tercakup di atas.

## Input Validation & Output Escaping

- Semua input wajib divalidasi via Laravel Form Request (`FormRequest` class), bukan validasi inline di controller yang mudah terlewat.
- Blade escaping (`{{ }}`) dipakai default untuk semua output user-generated content (bio, post, comment, confession nanti) — `{!! !!}` (raw HTML) **dilarang** untuk konten dari user, mencegah stored XSS. Ini penting karena produk ini penuh user-generated content (feed, gallery caption, timeline story).

## Mass Assignment Protection

Setiap Eloquent model wajib eksplisit mendefinisikan `$fillable` (bukan `$guarded = []`) — mencegah field sensitif (mis. `role` di `class_memberships`, `visibility` admin-only) ter-update tidak sengaja lewat request yang dimanipulasi.

## Rate Limiting di Luar Login

- Endpoint yang rawan disalahgunakan untuk spam (create post, comment, report) diberi rate limit tambahan (mis. `throttle:20,1` — 20 request/menit) — bukan hanya login yang dilindungi.
- Voting (`poll_votes`) dan reaction sudah dilindungi lewat unique constraint database (lihat `03-database.md`), bukan hanya rate limit, karena unique constraint jauh lebih kuat mencegah manipulasi.

## Data Sensitif dalam Log & Error

- `APP_DEBUG=false` wajib di production — stack trace Laravel tidak boleh terekspos ke user (bisa membocorkan struktur database/kode).
- Log aplikasi tidak boleh mencatat password, token, atau isi confession (saat fitur itu dikerjakan) dalam bentuk plain text.

## Dependency & Update

- `composer.lock` dan `package-lock.json` (jika ada dependency JS) di-commit ke repo — memastikan versi dependency konsisten antar anggota tim, bukan sumber bug "di laptop saya jalan".
- Cek `composer audit` secara berkala (manual di MVP, otomatis lewat CI dipertimbangkan di [`12-testing.md`](./12-testing.md)).

## Backup

- Database di-backup harian (mekanisme mengikuti pilihan hosting di [`13-deployment.md`](./13-deployment.md)) — penting khusus untuk produk ini karena prinsip *Memory Forever* berarti kehilangan data = kehilangan kenangan kelas secara permanen, bukan sekadar downtime teknis.

## Yang Sengaja Tidak Dibangun di MVP

- WAF (Web Application Firewall) khusus, penetration testing formal, atau security audit eksternal — di luar skala kebutuhan proyek tugas kelompok. Dipertimbangkan lagi jika produk benar-benar dipakai lintas kampus.
- 2FA (two-factor authentication) — ditunda ke P2+, MVP cukup dengan password + rate limit + verifikasi email.
