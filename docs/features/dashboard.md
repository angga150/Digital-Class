# Dashboard

## Status

UI demo diimplementasikan (6 Oktober 2026); integrasi data dan autentikasi masih planned.

Dashboard memakai halaman Livewire, komponen Blade di `src/resources/views/components/dashboard/`, Tailwind CSS, dan Alpine bawaan Livewire untuk interaksi demo. Desain mengacu pada `docs/design/dashboard-concept-v1.png`.

- Layout desktop dengan sidebar; HP dengan navigasi bawah dan kartu kabar sebelum feed.
- Pengumuman, agenda, ulang tahun, cerita, polling, dan album memakai data contoh. Tanggal contoh mengikuti waktu Asia/Jakarta.
- Tulis cerita, komentar, suka, penanda, pencarian data contoh, dan voting berjalan sebagai simulasi sementara di browser. Tidak ada penyimpanan database/local storage, unggah, login, atau request mutasi produksi; refresh mengembalikan demo ke kondisi awal.
- Dialog menggunakan elemen native `dialog` (focus trap, Escape), label form, status/error yang dapat dibaca pembaca layar, dan dukungan reduced motion.
- Foto ilustrasi AI tersimpan lokal di `src/public/img/dashboard/campus-friends.png`; bukan foto anggota asli.

Spesifikasi di bawah tetap merupakan target fitur produksi. Sebelum mengganti data contoh dengan data asli, implementasikan auth, membership, visibility, Policy, dan validasi server. Interaksi Alpine demo bukan pengganti aturan bisnis tersebut.

## Tujuan

Halaman pertama yang dilihat user setelah login. Tujuannya memberi alasan nyata bagi mahasiswa untuk membuka website **setiap hari** — bukan cuma saat butuh info formal. Menampilkan info yang "terasa hidup": ulang tahun terdekat, event terdekat, aktivitas terbaru, polling aktif, achievement terbaru.

## User Flow

```
User login
  ↓
Diarahkan langsung ke Dashboard (bukan feed atau halaman kosong)
  ↓
Melihat: sapaan, birthday terdekat, event countdown, aktivitas terbaru,
         polling aktif, achievement terbaru
  ↓
Klik salah satu widget → diarahkan ke halaman detail terkait
```

## Business Rules

- Dashboard bukan tabel tersendiri di database — ini adalah gabungan (agregat) dari data fitur lain (profil, event, post, poll, achievement).
- Kalau kelas masih baru dan belum ada aktivitas, tampilkan pesan ramah ("Belum ada aktivitas, jadilah yang pertama posting!"), bukan widget kosong tanpa penjelasan.
- Semua widget tetap menghormati `visibility` masing-masing data — dashboard tidak boleh jadi celah yang menampilkan data yang seharusnya tersembunyi.

## Database

Tidak ada tabel baru. Mengambil data dari: `profiles` (birthday), `events`, `posts`, `polls`, `user_achievements`. Lihat [`../04-database.md`](../04-database.md).

## Backend

- Buat Livewire component terpisah untuk tiap widget (`DashboardGreeting`, `DashboardBirthday`, `DashboardEvent`, dst.) — bukan satu component besar, supaya query lambat di satu widget tidak memblokir widget lain.
- Tiap widget query data terbaru sesuai kebutuhannya sendiri (misal: birthday terdekat = urutkan `birth_date` terhitung dari tanggal hari ini).

## Frontend

- Layout grid (card-based), 1 kolom di mobile, beberapa kolom di desktop (lihat prinsip mobile-first di [`../03-architecture.md`](../03-architecture.md)).
- Tiap widget pakai `<x-card>`.
- Widget yang datanya kosong tampilkan `<x-empty-state>`, bukan card kosong tanpa teks.

## Permission

Semua role yang sudah login (Member ke atas) bisa melihat dashboard. Isi tiap widget tetap difilter sesuai visibility & role.

## Validation

Tidak ada input user langsung di halaman ini (murni tampilan data).

## Edge Cases

- Tidak ada birthday dalam 30 hari ke depan → widget tampilkan pesan netral, bukan disembunyikan total (supaya layout tidak "meloncat").
- User baru gabung, belum ada achievement sama sekali → tampilkan pesan ajakan ("Lengkapi profil untuk dapat badge pertamamu!").

## Testing

Tidak perlu test kompleks — cukup pastikan tiap widget menghormati visibility (test manual dengan role berbeda cukup di MVP).

## Related Features

Birthday System, Event Countdown, Social Feed, Polling, Achievement System — dashboard menampilkan ringkasan dari semuanya.
