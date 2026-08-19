# Feature: Class Dashboard

Status: ✅ Spesifikasi final. Referensi produk: [`product/02-problem-solution-feature-mapping.md`](../../product/02-problem-solution-feature-mapping.md) §"HOME / CLASS DASHBOARD".

## User Flow

1. User login → landing page adalah dashboard (bukan feed atau halaman kosong).
2. Dashboard menampilkan widget: sapaan bernama, birthday terdekat, event countdown, aktivitas terbaru, polling aktif, achievement terbaru.
3. Setiap widget adalah Livewire component independen (`DashboardGreeting`, `DashboardBirthday`, dst.) — bukan satu component monolitik — agar bisa di-lazy-load dan tidak saling blocking saat query lambat.

## Data Model

Tidak ada tabel `dashboard` sendiri — ini adalah view agregat dari tabel lain. Lihat [`03-database.md`](../03-database.md): `profiles` (birthday), `events`, `posts`, `polls`, `user_achievements`.

## API

`GET /api/v1/dashboard` — lihat [`04-api.md`](../04-api.md) §Dashboard. Untuk web (Livewire), tiap widget query langsung ke model terkait, tidak melalui endpoint API ini.

## Edge Case & Validasi

- Kelas baru tanpa data historis (belum ada post/event) → tampilkan empty state yang tetap ramah ("Belum ada aktivitas, jadilah yang pertama posting!"), bukan widget kosong tanpa penjelasan.
- Semua widget menghormati `visibility` milik masing-masing record — dashboard tidak boleh jadi celah yang menampilkan data yang seharusnya tersembunyi.

## Permission

Semua role yang login (member ke atas) bisa melihat dashboard. Konten tiap widget tetap difilter sesuai visibility & role masing-masing (lihat [`06-authorization.md`](../06-authorization.md)).
