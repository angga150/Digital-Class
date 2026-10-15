# Birthday System

## Status

Kalender dan widget ulang tahun diimplementasikan pada 16 Oktober 2026 di direktori utama `C:/laragon/www/website kelas`, aplikasi Laravel di `src/`. Stack tetap Livewire dan Tailwind.

## Implementasi saat ini

- `/birthdays` menampilkan daftar perayaan berikutnya, diurutkan dari tanggal terdekat, dengan filter bulan dan ringkasan hari ini/30 hari ke depan. Setiap kartu menuju profil anggota; tautan pengaturan menuju Profilku.
- Widget dashboard mengganti data contoh “Rani” dengan anggota nyata yang ulang tahunnya paling dekat dalam 30 hari. Semua anggota pada tanggal terdekat yang sama ditampilkan. Jika tidak ada yang boleh ditampilkan, muncul pesan kosong yang ramah.
- Tanggal hari ini dan selisih hari memakai WIB (`Asia/Jakarta`). Tanggal yang sudah lewat dipindahkan ke perayaan tahun berikutnya. Untuk 29 Februari, perayaan jatuh pada 28 Februari di tahun biasa dan tetap 29 Februari pada tahun kabisat.
- Halaman dan widget memperbarui data setiap 60 detik ketika aktif. Status dihitung saat dibaca, tanpa scheduler atau email otomatis.
- Tidak menampilkan usia atau tahun lahir, termasuk jika pemilik mengizinkan tahun lahir di halaman profil. Tahun pada tanggal “Perayaan berikutnya” adalah tahun perayaan mendatang.
- Shortcut pencarian dashboard dan tombol kalender membuka halaman asli. Modal ulang tahun contoh tidak dipakai lagi. Papan notifikasi secara keseluruhan masih demo.

## Privasi dan sumber data

Sumber data adalah `profiles.birth_date` yang diisi sendiri oleh pemilik. Tidak mengambil tanggal/nickname contoh lama dari `class_members`. Tidak ada migration, seeder, atau perubahan data profil otomatis pada tahap ini.

`MemberProfile` tetap menjadi sumber aturan akses. Bulan dan hari baru diteruskan jika **keduanya**, `profile_visibility` dan `birthday_visibility`, mengizinkan pembaca: `class_only`, `admin_only`, atau `private`. Pemilik dapat melihat datanya sendiri. Pengurus tidak dapat melewati pengaturan `private`.

Berbeda dari ide sapaan privat dalam rancangan awal di bawah, ulang tahun privat **tidak** memunculkan nama, tanggal, hitungan, atau sapaan bagi pembaca lain. Penyaringan dilakukan sebelum pengurutan, filter bulan, dan ringkasan jumlah. Ini mempertahankan janji privasi di halaman profil yang sudah berjalan.

`ClassBirthdays` mengambil membership kelas aktif, memakai presenter, lalu hanya membentuk data kartu yang aman. Model profil mentah, email, usia, dan tanggal lahir lengkap tidak disimpan di state publik Livewire. Izin diperiksa kembali ketika komponen diperbarui, sehingga perubahan privasi, role, atau kelas aktif ikut berlaku pada request berikutnya.

Form Profilku yang sudah ada masih menerima tanggal lengkap `YYYY-MM-DD` secara opsional; pengguna boleh tidak mengisinya atau menghapusnya. Input hanya hari/bulan tanpa tahun belum tersedia dan menjadi tahap lanjutan, karena skema saat ini menggunakan satu kolom tanggal. Pengaturan `public` juga belum tersedia; seluruh halaman tetap hanya untuk anggota kelas aktif. Notifikasi terjadwal, pengiriman ucapan, dan integrasi kalender eksternal belum termasuk tahap ini.

## Verifikasi

Seluruh suite lulus: **63 tes, 469 assertions**, termasuk 10 tes Kalender Ulang Tahun. Build aset dan Pint juga lulus. Tahap ini tidak membutuhkan migration baru.

Tes fitur meliputi tanggal yang sama, aturan privasi pemilik/pengurus/anggota, tidak tampilnya tahun lahir atau email, pergantian tanggal WIB, pergantian tahun, 29 Februari, batas 30 hari, filter bulan, pemisahan kelas, profil kosong, data contoh lama, perubahan privasi saat halaman terbuka, penghapusan tanggal oleh pemilik, login, serta escaping nama. Jalankan `php artisan test` dan `npm run build` dari `src/`. Tes menggunakan SQLite in-memory; server tidak dinyalakan dan tampilan interaktif browser belum diperiksa.

## Rancangan awal untuk pengembangan lanjutan

Bagian berikut dipertahankan sebagai konteks rancangan. Jika berbeda, catatan implementasi di atas menjelaskan perilaku saat ini, terutama mengenai privasi dan tahun lahir opsional.

## Tujuan

Supaya tidak ada lagi yang lupa ulang tahun teman sekelas — sekaligus jadi salah satu alasan emosional utama orang buka dashboard tiap hari.

## User Flow

```
User mengisi profil
  ↓
Isi tanggal lahir (tahun opsional) + pilih visibility
  ↓
Sistem hitung otomatis siapa yang ulang tahun dalam waktu dekat
  ↓
Tampil di Dashboard & halaman Birthday Calendar
  ↓
Pada hari-H, sistem tandai "hari ini ulang tahun X" (sesuai visibility)
```

## Business Rules

- **Tahun lahir tidak pernah wajib** — user boleh isi tanggal+bulan saja.
- Visibility birthday: `public` / `class_only` (default) / `private`.
- Kalau `private`, sistem tetap boleh menandai "hari ini ulang tahun X" untuk keperluan sapaan sosial, tapi **tidak menampilkan tanggal/tahun lahir eksak** ke user lain.
- Tahun kabisat (29 Februari) dirayakan di 28 Februari pada tahun biasa.

## Database

Field ada di tabel `profiles`: `birth_date`, `birth_year_visible`, `birthday_visibility`. Tidak ada tabel terpisah — birthday adalah atribut profil. Lihat [`../04-database.md`](../04-database.md).

## Backend

- Query "birthday terdekat" = urutkan seluruh member berdasarkan jarak `birth_date` (bulan+tanggal saja) dari hari ini.
- Logic penentuan "hari ini ulang tahun siapa" dijalankan tiap hari (bisa lewat scheduled task Laravel).

## Frontend

- Widget di Dashboard: "🎂 Birthday berikutnya: [nama] — [x] hari lagi"
- Halaman Birthday Calendar: list/kalender semua birthday sesuai visibility yang boleh dilihat viewer
- Form isi tanggal lahir di halaman edit profil, dengan toggle visibility di sebelahnya (bukan tersembunyi di menu terpisah)

## Permission

Setiap user hanya bisa mengubah `birth_date`/visibility miliknya sendiri. Class Admin **tidak bisa** mengubah birthday member lain.

## Validation

- `birth_date` harus tanggal valid, tidak boleh di masa depan
- `birthday_visibility` harus salah satu dari `public`/`class_only`/`private`

## Edge Cases

- User tidak mengisi `birth_date` sama sekali → tidak muncul di widget manapun, tidak error
- Dua orang ulang tahun di hari yang sama → dua-duanya tampil, bukan cuma satu

## Testing

**Wajib ditest**: visibility `private` benar-benar tidak membocorkan tanggal/tahun lahir ke user lain, meski status "hari ini ulang tahun" tetap muncul.

## Related Features

Dashboard, Member Profile
