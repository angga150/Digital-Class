# Feature Specification: Birthdays

## 1. Status
Active (MVP Scope)

## 2. Tujuan
Menampilkan daftar ulang tahun teman sekelas terdekat beserta hitung mundur (*countdown*) otomatis dan fitur pengiriman ucapan singkat agar suasana kelas lebih akrab dan sosial.

## 3. User Flow
1. Pengguna membuka halaman Beranda atau menu Birthdays.
2. Sistem menampilkan daftar ulang tahun anggota kelas bulan ini.
3. Kartu ulang tahun menampilkan sisa hari (hitung mundur) menuju hari H.
4. Pengguna dapat memberikan ucapan selamat ulang tahun langsung pada kartu profil terkait.

## 4. Business Rules
- Tanggal lahir diambil dari data profil tabel `users`.
- Hitung mundur dihitung otomatis berdasarkan tanggal hari ini terhadap tanggal ulang tahun di tahun berjalan.
- Jika hari ulang tahun adalah hari ini, tampilkan lencana khusus ("🎉 Hari Ini!").

## 5. Database & Model
- Menggunakan kolom `birth_date` pada tabel `users`.
- Model: `User.php`.

## 6. Tugas Backend
- Membuat query Eloquent untuk menyaring dan mengurutkan pengguna berdasarkan tanggal ulang tahun terdekat.
- Menambahkan validasi input profil tanggal lahir.

## 7. Tugas Frontend
- Membuat komponen Livewire `BirthdayCountdown`.
- Mendesain kartu profil ulang tahun menggunakan Tailwind CSS dengan efek visual yang meriah (ikon kue/balon).
- Menyiapkan *loading state* saat mengambil data.

## 8. Permission & Security
- Terbuka untuk seluruh `member` terautentikasi.
- Pengguna hanya dapat mengubah tanggal lahir mereka sendiri melalui halaman pengaturan profil.

## 9. Testing
- Memastikan perhitungan selisih hari ulang tahun akurat pada berbagai kondisi tanggal.
- Memastikan tampilan responsif di perangkat mobile.
