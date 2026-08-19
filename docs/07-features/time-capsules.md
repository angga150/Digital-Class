# Feature: Time Capsule

> 🟡 **Status: Sengaja ditunda ke P1.** Bukan skeleton menunggu dependensi teknis — semua fondasi (auth, database, API) sudah selesai. Fitur ini ditunda murni karena keputusan prioritas produk.

## Kenapa Ditunda

Time Capsule dievaluasi di [`product/02-problem-solution-feature-mapping.md`](../../product/02-problem-solution-feature-mapping.md) §D3 dan diputuskan **P1**: fitur bagus secara emosional tapi tidak kritikal di hari-hari awal, lebih cocok diluncurkan setelah engagement dasar (feed, gallery, timeline) sudah terbentuk. Tabel untuk fitur ini juga sengaja tidak ada di [`03-database.md`](../03-database.md) — akan ditambahkan lewat migration baru saat fitur ini mulai dikerjakan, bukan disiapkan lebih dulu (prinsip *Simple First*: tabel kosong yang belum dipakai hanya menambah kebingungan).

## Rancangan Awal (untuk referensi saat P1 dimulai, belum final)

Berdasarkan spesifikasi produk asli:

- Data: `author_id`, `message`, `created_at`, `unlock_at`, `visibility`, `status`
- Aturan inti: pesan **tidak boleh** bisa dibuka (dibaca isinya) sebelum `unlock_at` — validasi ini harus di level server/query, bukan hanya disembunyikan di UI
- Perlu penanganan timezone eksplisit (`unlock_at` disimpan UTC, dikonversi saat ditampilkan — pola sama dengan `events.starts_at` di [`03-database.md`](../03-database.md))

## Yang Harus Diisi Saat Dikerjakan

- [ ] User flow lengkap
- [ ] Data model final (migration baru)
- [ ] Endpoint API terkait
- [ ] Edge case (mis. apa yang terjadi jika user keluar dari kelas sebelum `unlock_at` tiba)
- [ ] Permission
