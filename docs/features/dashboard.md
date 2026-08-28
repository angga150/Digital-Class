# Class Dashboard

> **Status:** ✅ Spesifikasi final. Referensi produk: [`product/02-problem-solution-feature-mapping.md`](../02-problem-solution-feature-mapping.md) §"HOME / CLASS DASHBOARD".

Dokumen ini adalah spesifikasi kerja untuk feature **Class Dashboard**. Developer yang mendapat task ini seharusnya dapat membaca dokumen ini lalu mulai coding tanpa perlu membaca seluruh dokumentasi repository.

## Cara Membaca

Urutan praktis:

1. Pahami tujuan dan user flow.
2. Periksa business rules dan permission.
3. Lihat data model.
4. Implement backend + Livewire + UI.
5. Tangani validation dan edge cases.
6. Tambahkan test untuk logic kritikal.
7. Update dokumentasi bila ada perubahan keputusan.

## User Flow

1. User login → landing page adalah dashboard (bukan feed atau halaman kosong).
2. Dashboard menampilkan widget: sapaan bernama, birthday terdekat, event countdown, aktivitas terbaru, polling aktif, achievement terbaru.
3. Setiap widget adalah Livewire component independen (`DashboardGreeting`, `DashboardBirthday`, dst.) — bukan satu component monolitik — agar bisa di-lazy-load dan tidak saling blocking saat query lambat.

## Data Model

Tidak ada tabel `dashboard` sendiri — ini adalah view agregat dari tabel lain. Lihat [`../04-database.md`](../04-database.md): `profiles` (birthday), `events`, `posts`, `polls`, `user_achievements`.

## API

`GET /api/v1/dashboard` — lihat [`../03-architecture.md`](../03-architecture.md) §Dashboard. Untuk web (Livewire), tiap widget query langsung ke model terkait, tidak melalui endpoint API ini.

## Edge Case & Validasi

- Kelas baru tanpa data historis (belum ada post/event) → tampilkan empty state yang tetap ramah ("Belum ada aktivitas, jadilah yang pertama posting!"), bukan widget kosong tanpa penjelasan.
- Semua widget menghormati `visibility` milik masing-masing record — dashboard tidak boleh jadi celah yang menampilkan data yang seharusnya tersembunyi.

## Permission

Semua role yang login (member ke atas) bisa melihat dashboard. Konten tiap widget tetap difilter sesuai visibility & role masing-masing (lihat [`../03-architecture.md`](../03-architecture.md)).

## Testing

Prioritaskan happy path, validation error, permission/visibility, dan edge case yang berisiko mengubah data atau membocorkan data.

## Related Documentation

- [Product](../01-product.md)
- [Development](../02-development.md)
- [Architecture](../03-architecture.md)
- [Database](../04-database.md)
