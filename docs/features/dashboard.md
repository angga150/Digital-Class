# Feature Specification: Dashboard

## 1. Status
Active (MVP Scope)

## 2. Tujuan
Menjadi halaman utama yang menyambut pengguna setelah login, menampilkan ringkasan aktivitas kelas, widget ulang tahun terdekat, pengumuman terbaru, dan pintasan cepat ke fitur lainnya.

## 3. User Flow
1. Pengguna login ke aplikasi.
2. Sistem mengarahkan ke halaman `/dashboard`.
3. Pengguna melihat ringkasan widget (ulang tahun terdekat, polling aktif, dan pengumuman terbaru).

## 4. Business Rules
- Menampilkan data yang bersifat *real-time* atau ringkasan terbaru dari berbagai modul fitur.
- Desain *mobile-first* agar mudah dipahami dalam sekali lihat (*at a glance*).

## 5. Tugas Backend & Frontend
- **Backend:** Menyediakan *method* di Livewire Dashboard component untuk mengambil data agregat dari berbagai model (`User`, `Post`, `Poll`).
- **Frontend:** Menyusun layout grid Tailwind CSS yang bersih, responsif, dengan penanganan *empty state* jika belum ada pengumuman.
