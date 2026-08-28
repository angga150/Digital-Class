# Birthday System

> **Status:** ✅ Spesifikasi final. Referensi produk: [`product/02-problem-solution-feature-mapping.md`](../02-problem-solution-feature-mapping.md) §B1.

Dokumen ini adalah spesifikasi kerja untuk feature **Birthday System**. Developer yang mendapat task ini seharusnya dapat membaca dokumen ini lalu mulai coding tanpa perlu membaca seluruh dokumentasi repository.

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

1. Saat mengisi profil, user mengisi `birth_date` (wajib tanggal+bulan, tahun opsional lewat toggle `birth_year_visible`).
2. User memilih `birthday_visibility` (`public`/`class_only`/`private`).
3. Dashboard & halaman "Birthday Calendar" menampilkan birthday terdekat sesuai visibility masing-masing user terhadap viewer.
4. Pada hari H, sistem menandai user tersebut sebagai "berulang tahun hari ini" di dashboard — tanpa membuka detail tanggal lahir jika visibility-nya `private`.

## Data Model

Field ada di tabel `profiles` (lihat [`../04-database.md`](../04-database.md)): `birth_date`, `birth_year_visible`, `birthday_visibility`. Tidak ada tabel terpisah — birthday adalah atribut profil, bukan entitas sendiri.

## API

`GET /api/v1/birthdays/upcoming` — lihat [`../03-architecture.md`](../03-architecture.md).

## Edge Case & Validasi

- User tidak mengisi `birth_date` sama sekali → tidak muncul di widget birthday manapun, tidak error.
- `birthday_visibility = private` → sistem tetap boleh menandai "hari ini ulang tahun X" (untuk keperluan sapaan sosial), tapi **tidak** menampilkan tanggal/tahun lahir eksak ke user lain.
- Tahun kabisat (29 Februari) → tampilkan perayaan di 28 Februari pada tahun non-kabisat (aturan bisnis sederhana, dicatat di kode agar tidak ambigu).

## Permission

Setiap user hanya bisa mengubah `birth_date`/visibility miliknya sendiri (`ProfilePolicy@update`, lihat [`../03-architecture.md`](../03-architecture.md)). Class Admin tidak bisa mengubah birthday member lain.

## Testing

Prioritaskan happy path, validation error, permission/visibility, dan edge case yang berisiko mengubah data atau membocorkan data.

## Related Documentation

- [Product](../01-product.md)
- [Development](../02-development.md)
- [Architecture](../03-architecture.md)
- [Database](../04-database.md)
