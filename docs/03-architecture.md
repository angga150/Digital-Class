# Architecture

Dokumen ini menjelaskan **bagaimana project ini bekerja secara teknis**. Kalau kamu sudah bisa menjalankan project (lihat [`02-development.md`](./02-development.md)) dan sekarang ingin paham kenapa struktur kodenya begini, mulai dari sini.

Keputusan teknologi di bawah ini **sudah final** — tidak perlu dipertimbangkan ulang setiap mulai fitur baru.

## 1. Tech Stack

| Bagian | Teknologi |
|---|---|
| Backend / application framework | **Laravel** |
| Interactive UI | **Livewire** |
| Styling | **Tailwind CSS** |
| Database | **MySQL** |
| JavaScript tambahan | **Alpine.js** — dipakai hanya kalau memang dibutuhkan (misalnya dropdown atau modal kecil), bukan default di semua tempat |

Kita **tidak** memakai React, Vue, Inertia, atau arsitektur SPA (Single Page Application) terpisah. Satu aplikasi Laravel menangani semuanya — backend dan tampilan interaktif jadi satu, bukan dua project terpisah yang saling panggil API.

## 2. High-Level Architecture

```
Browser
  ↓
Laravel (routing, business logic, auth)
  ↓
Livewire (component interaktif)
  ↓
Eloquent ORM
  ↓
MySQL
```

Penjelasan tiap bagian:

- **Laravel** menangani routing, business logic, authentication, dan komunikasi ke database.
- **Livewire** menangani bagian UI yang perlu interaktif (klik tombol tanpa reload halaman, form yang langsung validasi, dsb.) — tanpa kita perlu membangun frontend SPA terpisah.
- **Tailwind CSS** dipakai untuk semua styling — bukan CSS custom manual.
- **Eloquent** adalah ORM (Object-Relational Mapping) bawaan Laravel — cara kita "bicara" ke database pakai kode PHP biasa, bukan menulis query SQL manual.

## 3. Struktur Kode (Modular per Domain)

Aplikasi disusun sebagai **satu aplikasi Laravel** (bukan dipecah `backend/` dan `frontend/`), tapi kode di dalamnya dikelompokkan per topik fitur (domain), supaya tidak semua model/component numpuk jadi satu tumpukan besar yang membingungkan.

Kelompok domain yang dipakai di project ini:

- **Membership & Auth** — login, registrasi, profil, role
- **Dashboard & Birthday/Event** — dashboard, birthday system, event countdown
- **Feed & Poll** — social feed, polling
- **Gallery & Timeline** — gallery, class timeline
- **Achievement & Notification** — achievement system, notifikasi in-app
- **Moderation** — privacy/visibility, moderation, audit log — dipakai oleh hampir semua fitur lain, jadi butuh perhatian ekstra saat diubah

Semua tabel yang menyimpan data milik satu kelas **wajib** punya kolom `class_id`, dan query-nya selalu difilter otomatis lewat *global scope* Eloquent — supaya tidak ada kemungkinan data satu kelas ketampil ke kelas lain, bahkan kalau nanti ada banyak kelas di satu aplikasi yang sama. Detail lengkap ada di [`04-database.md`](./04-database.md).

## 4. Alur Kerja Satu Fitur (Frontend + Backend Jadi Satu Alur)

Karena stack kita Laravel + Livewire, satu fitur **selalu melewati alur yang sama** — tidak ada dunia "backend" dan "frontend" yang terpisah:

```
User berinteraksi di browser
  ↓
Livewire Component (menangkap aksi user)
  ↓
Validation (cek input benar/salah)
  ↓
Model / Eloquent (baca-tulis ke database)
  ↓
MySQL
  ↓
Livewire State (data terbaru disimpan di component)
  ↓
Blade (template ditampilkan ulang)
  ↓
Tailwind CSS (styling)
  ↓
Browser (user lihat hasilnya, tanpa reload halaman)
```

Ini kenapa dokumentasi fitur di [`docs/features/`](./features/) selalu menjelaskan bagian Backend dan Frontend **bersebelahan**, bukan sebagai dua dunia terpisah — karena satu fitur memang jalan lewat satu alur yang sama.

## 5. Boundary: Kalau Kamu Fokus Backend vs Frontend

Kalau tim kamu ada yang lebih nyaman di sisi data/logic dan ada yang lebih nyaman di sisi tampilan, ini pembagian fokus yang wajar dipakai — **tapi bukan aturan kaku**. Satu orang boleh dan wajar mengerjakan keduanya sekaligus untuk fitur kecil.

| Fokus Backend | Fokus Frontend |
|---|---|
| Migration | Blade |
| Model & relationship | Livewire UI (tampilan komponen) |
| Business logic | Tailwind CSS |
| Validation | Responsive design |
| Authorization (Policy) | Form UI |
| Livewire actions (logic di balik tombol) | Loading state, empty state, error state |
| Testing | Mobile UI |

Contoh kenapa keduanya tetap saling berhubungan dalam satu fitur (Birthday System):

```
Backend: data tanggal lahir tersimpan di database
  ↓
Livewire state: countdown dihitung di component
  ↓
Frontend: ditampilkan sebagai "🎂 3 hari lagi" di dashboard
```

Backend menyiapkan datanya, frontend yang menampilkannya jadi sesuatu yang terasa hidup — dua-duanya perlu paham fiturnya secara keseluruhan, bukan cuma potongan yang jadi tugasnya.

## 6. UI Principles

Karena mayoritas mahasiswa akan mengakses lewat HP, **mobile-first** adalah prinsip utama — bukan sekadar "juga harus responsive". Desain dan styling ditulis untuk layar kecil dulu, baru diperluas ke layar besar.

Prinsip lain:

- **Konsisten** — spacing, warna, dan komponen dipakai berulang, bukan tiap halaman punya gaya sendiri. Pakai Blade component (`<x-card>`, `<x-button>`, `<x-avatar>`, dsb.) supaya style tidak diketik ulang beda-beda oleh tiap anggota tim.
- **Reusable component** — kalau satu elemen dipakai di lebih dari satu tempat (badge achievement, tombol, avatar), buat jadi Blade component sekali, jangan copy-paste HTML.
- **Loading state jelas** — Livewire punya `wire:loading` bawaan, selalu dipakai saat ada aksi yang butuh waktu (submit form, upload foto).
- **Empty state jelas** — kalau data masih kosong (belum ada post, gallery kosong), tampilkan pesan yang ramah ("Belum ada aktivitas, jadilah yang pertama posting!"), bukan halaman kosong tanpa penjelasan.
- **Error state jelas** — pesan error harus manusiawi, bukan menampilkan error teknis mentah ke user.
- **Aksesibel secukupnya** — semua form input punya `<label>` (bukan cuma placeholder), gambar punya `alt` text, kontras warna teks cukup terbaca. Tidak perlu audit aksesibilitas formal di MVP, cukup kebiasaan dasar ini dijaga konsisten.
- **Warna & nuansa** — pakai warna aksen hangat (oranye/kuning), rounded corner, kesan santai dan personal — selaras prinsip *Fun First*, bukan tone korporat kaku.

## 7. Security Basics

Security tetap penting, tapi tidak perlu berlebihan (over-engineering) untuk skala project ini. Berikut yang **wajib** diperhatikan setiap fitur baru:

### Input Validation

Semua input dari user **wajib** divalidasi lewat Form Request atau `rules()` di Livewire component — jangan percaya input mentah dari user, bahkan kalau terasa "kecil kemungkinan disalahgunakan".

### Authentication

Login memakai session-based auth bawaan Laravel (Laravel Breeze). Registrasi **tidak terbuka bebas** — user daftar lewat *join code* yang dibuat Class Admin (bukan open signup), karena ini ruang privat satu kelas. Email wajib diverifikasi sebelum bisa posting/upload.

### Authorization

Setiap aksi yang mengubah data (buat, edit, hapus) **wajib** dicek lewat Laravel Policy — merujuk role user di `class_memberships`. Jangan hanya menyembunyikan tombol di UI; validasi izin harus tetap dicek di server, karena user teknis bisa memanggil aksi langsung tanpa lewat tombol.

Selain role, ada juga pengecekan **visibility** (field-level) — dua hal berbeda: Policy jawab "boleh ngapain", visibility jawab "boleh lihat data apa". Contoh: Member yang sah boleh lihat profil orang lain, tapi tetap tidak boleh lihat tanggal lahir yang di-set `private`.

### XSS (Cross-Site Scripting)

Semua output dari user (bio, post, comment, caption) ditampilkan lewat `{{ }}` Blade (otomatis di-escape) — **jangan pernah** pakai `{!! !!}` (raw HTML) untuk konten dari user, karena itu bisa dipakai menyisipkan script berbahaya.

### CSRF (Cross-Site Request Forgery)

Sudah otomatis ditangani Laravel untuk semua form Blade/Livewire — tidak perlu setup manual, tapi jangan pernah dimatikan (`@csrf` tidak boleh dihapus dari form biasa).

### File Upload

Setiap upload (avatar, foto gallery) wajib divalidasi di server: tipe file (`jpg`, `jpeg`, `png`, `webp`), ukuran maksimum (5 MB), bukan hanya dicek di sisi browser.

### Environment Secrets

`.env` **tidak pernah** masuk ke Git (sudah diatur di `.gitignore`). Kalau butuh menambah environment variable baru, tambahkan juga ke `.env.example` (tanpa isi rahasianya) supaya anggota tim lain tahu variabel itu ada.

### User Privacy

Field sensitif (tanggal lahir, kontak, dll.) selalu punya default visibility `class_only`, bukan `public` — user boleh menaikkan visibility-nya sendiri, tidak dipaksa menurunkan.

### Checklist Sebelum Pull Request

```
[ ] Input sudah divalidasi
[ ] Authorization (Policy) sudah dicek, bukan cuma disembunyikan di UI
[ ] File upload (kalau ada) divalidasi tipe & ukurannya
[ ] Tidak ada secret/password/API key di kode yang di-commit
[ ] Data privat (birthday, dsb.) tidak bocor ke user yang tidak berhak
```

## 8. Kenapa Bukan REST API Penuh?

Frontend kita adalah Blade + Livewire, bukan aplikasi terpisah (SPA/mobile app) — jadi kita **tidak** membangun REST API penuh di MVP. Livewire component langsung bicara ke Model/Eloquent tanpa lapisan API di tengahnya. Kalau nanti benar-benar butuh (misalnya ada mobile app terpisah), endpoint API bisa ditambahkan belakangan tanpa mengubah cara kerja Livewire yang sudah ada — tapi ini bukan sesuatu yang perlu disiapkan sekarang.
