# Architecture Overview

Digital Class Universe menggunakan arsitektur monolitik yang bersih, sederhana, dan terstruktur sesuai standar framework Laravel, dipadukan dengan Livewire untuk antarmuka interaktif tanpa kompleksitas pengembangan SPA (Single Page Application) terpisah.

## 1. Tech Stack Summary
- **Backend / Application Framework:** Laravel (Menangani routing, autentikasi, business logic, interaksi database Eloquent ORM).
- **Frontend / Interactive UI:** Livewire (Memungkinkan komponen reaktif berbasis server-side PHP tanpa perlu menulis API terpisah dengan Vue/React).
- **Styling:** Tailwind CSS (Framework utility-first untuk desain UI yang cepat dan responsif).
- **Database:** MySQL (Penyimpanan data relasional kelas).

## 2. High-Level Architecture Flow
```text
[ Browser / Client ]
        │  (HTTP Request / Livewire AJAX)
        ▼
[ Laravel Routing (`routes/web.php`) ]
        │
        ▼
[ Livewire Component / Controller ]
        │
        ▼
[ Eloquent ORM (Models) ]
        │
        ▼
[ MySQL Database ]
```

## 3. Penjelasan Komponen Utama
- **Laravel:** Berfungsi sebagai pondasi utama aplikasi. Mengatur autentikasi pengguna, manajemen hak akses (Policy/Middleware), serta komunikasi dengan database.
- **Livewire:** Menghubungkan interaksi pengguna di browser langsung dengan komponen PHP di backend. Sangat cocok untuk fitur reaktif seperti *live search*, polling, komentar real-time, dan *birthday countdown*.
- **Tailwind CSS:** Digunakan untuk seluruh kebutuhan penataan gaya visual. Mendukung pendekatan *mobile-first* agar nyaman diakses lewat smartphone.
- **Eloquent ORM:** Mempermudah manipulasi database menggunakan objek PHP tanpa harus menulis query SQL mentah yang rumit.

## 4. Prinsip Skalabilitas Sederhana
Kita menghindari *overengineering* (seperti microservices atau repository pattern yang berlebihan). Skalabilitas dijaga melalui:
- Struktur folder yang rapi dan konsisten sesuai standar Laravel.
- Pemisahan tanggung jawab yang jelas antara Backend (logika data) dan Frontend (tampilan Blade & Tailwind).
- Indeks database yang tepat pada kolom yang sering dicari.
