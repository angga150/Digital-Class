# Event Countdown

> **Status:** ✅ Spesifikasi final. Referensi produk: [`product/02-problem-solution-feature-mapping.md`](../02-problem-solution-feature-mapping.md) §A2.

Dokumen ini adalah spesifikasi kerja untuk feature **Event Countdown**. Developer yang mendapat task ini seharusnya dapat membaca dokumen ini lalu mulai coding tanpa perlu membaca seluruh dokumentasi repository.

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

1. Class Admin membuat event (judul, deskripsi, tanggal+waktu, lokasi, gambar opsional).
2. Event muncul di dashboard sebagai countdown ("Wisuda: 438 hari lagi") dan di halaman daftar event.
3. Member bisa melihat detail event, tidak bisa mengedit.
4. Saat tanggal event lewat, status otomatis berubah ke `completed` (job terjadwal harian, bukan realtime).

## Data Model

Tabel `events` — lihat [`../04-database.md`](../04-database.md). Kolom `starts_at` disimpan UTC, dikonversi ke timezone lokal (WIB/WITA/WIT sesuai kebutuhan) di layer presentasi Blade — bukan disimpan per-timezone di database.

## API

`GET/POST/PATCH/DELETE /api/v1/events` — lihat [`../03-architecture.md`](../03-architecture.md).

## Edge Case & Validasi

- `starts_at` di masa lalu saat pembuatan → tolak dengan validasi, kecuali admin eksplisit membuat entri retroaktif (flag terpisah, tidak masuk MVP).
- Event tanpa lokasi (online/TBA) → field `location` nullable, tampilkan "Lokasi menyusul" bukan string kosong.
- Countdown menampilkan hari, bukan jam/menit, untuk event >7 hari lagi (mengurangi noise); untuk event <24 jam tampilkan jam:menit.

## Permission

Create/update/delete: `Class Admin` & `Super Admin` saja (`EventPolicy@create/update/delete`). Read: semua role login, difilter oleh `visibility`.

## Testing

Prioritaskan happy path, validation error, permission/visibility, dan edge case yang berisiko mengubah data atau membocorkan data.

## Related Documentation

- [Product](../01-product.md)
- [Development](../02-development.md)
- [Architecture](../03-architecture.md)
- [Database](../04-database.md)
