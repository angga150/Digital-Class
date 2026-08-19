# TARGET USER ANALYSIS
## Digital Class Universe

Setiap persona dijelaskan lewat: kebutuhan, masalah, motivasi pakai sistem, fitur paling relevan, dan hak akses yang diperlukan.

---

## 1. PRIMARY USER — MEMBER (Mahasiswa Anggota Kelas)

**Siapa:** Mahasiswa biasa di kelas tersebut. Ini adalah user mayoritas — 90%+ dari total pengguna di MVP.

**Kebutuhan:**
- Tahu apa yang terjadi di kelas tanpa harus scroll WA
- Merasa "dikenal" dan terhubung dengan teman sekelas
- Punya ruang ekspresi yang santai, tidak formal
- Ingin momen-momen kuliah terekam, tidak hilang begitu saja

**Masalah yang dialami:**
- Info penting tenggelam di chat grup yang ramai
- Tidak kenal dekat sebagian teman sekelas meski sudah berbulan-bulan sekelas
- Foto-foto kelas tersebar di banyak HP, tidak ada satu tempat terpusat
- Lupa ulang tahun teman, lupa deadline tugas

**Motivasi menggunakan sistem:**
- **Emosional** (dominan): FOMO ulang tahun teman, penasaran lihat feed, ingin dikenang di timeline kelas
- **Praktis** (sekunder): cek jadwal, cek deadline, ikut voting

Ini penting: motivasi utama Member bukan "kebutuhan administratif" tapi **rasa ingin terhubung**. Desain onboarding dan dashboard harus menyasar sisi emosional ini duluan (prinsip *Fun First*), baru fungsi praktis menyusul.

**Fitur paling relevan:**
- Class Dashboard, Birthday System, Event Countdown
- Member Profile, Social Feed
- Class Gallery, Class Timeline
- Polling/Voting (partisipan)
- Achievement (penerima badge)

**Hak akses:**
- Baca semua konten class-only
- Buat post, comment, reaction sendiri
- Edit profil & privacy setting milik sendiri
- Vote pada polling
- Upload ke gallery/timeline (dengan moderasi)
- **Tidak bisa**: kelola user lain, ubah role, akses data finansial detail (kecuali diberi izin), moderasi konten orang lain

---

## 2. SECONDARY USER — KETUA KELAS / PENGURUS (Class Admin)

**Siapa:** Ketua kelas, wakil ketua, sekretaris, bendahara — biasanya 1-5 orang per kelas.

**Kebutuhan:**
- Alat untuk menyampaikan info resmi secara efisien ke seluruh kelas
- Kontrol atas konten yang sensitif (approve confession, kelola kas)
- Insight ringan soal aktivitas kelas (siapa aktif, siapa belum update profil, dst.)

**Masalah yang dialami:**
- Harus broadcast info berkali-kali di berbagai channel
- Sulit melacak siapa sudah baca pengumuman
- Kas kelas rawan disalahpahami/dipertanyakan transparansinya
- Tidak ada alat bantu untuk menjalankan voting dengan hasil yang jelas & tidak bisa dimanipulasi

**Motivasi menggunakan sistem:**
- Efisiensi kerja (mengurangi beban administratif)
- Akuntabilitas — ingin ada bukti/record bahwa tugasnya dijalankan transparan
- Legacy — ingin masa kepengurusannya "berbekas" di timeline kelas

**Fitur paling relevan:**
- Academic Corner (buat pengumuman, kelola jadwal)
- Event Countdown (buat & kelola event)
- Polling/Voting (buat polling resmi)
- Class Finance (jika diaktifkan)
- Confession moderation (approve/reject)
- Class Timeline (kurasi entri resmi)
- Dashboard analytics ringan (opsional, bukan prioritas MVP)

**Hak akses:**
- Semua hak akses Member, ditambah:
- Buat/edit/hapus pengumuman & event resmi
- Kelola & lihat hasil polling (termasuk yang admin-only)
- Approve/reject confession (jika masuk P1)
- Kelola transaksi kas (jika Class Finance diaktifkan)
- Undang/kelola member (tambah, nonaktifkan akun)
- **Tidak bisa** (tergantung desain): akses pengaturan platform-level (itu milik Super Admin)

---

## 3. SECONDARY USER — MODERATOR

**Siapa:** Bisa jadi orang yang sama dengan pengurus kelas, atau anggota terpisah yang dipercaya khusus untuk urusan konten (misal karena confession butuh kepercayaan berbeda dari urusan administratif).

**Kebutuhan:**
- Alat untuk memantau & menindak konten bermasalah dengan cepat
- Kejelasan SOP: kapan harus reject, kapan harus escalate ke Class Admin

**Masalah yang dialami:**
- Tidak ada visibilitas terpusat atas laporan/report dari member
- Sulit mengambil keputusan konsisten tanpa histori moderasi

**Motivasi menggunakan sistem:**
- Rasa tanggung jawab menjaga kelas tetap aman & sehat
- Ingin proses moderasi punya jejak (audit log) agar tidak dituduh subjektif

**Fitur paling relevan:**
- Moderation queue (report, hide, block)
- Confession approval
- Audit log

**Hak akses:**
- Lihat moderation queue & audit log
- Approve/reject/hide konten (post, comment, gallery, confession)
- **Tidak bisa**: kelola role/permission user, akses data finansial, ubah pengaturan kelas

---

## 4. SUPER ADMIN (Platform-level)

**Siapa:** Pemilik/pengelola platform secara keseluruhan — di MVP ini kemungkinan besar adalah pembuat produk sendiri (Anda/tim proyek).

**Kebutuhan:**
- Kontrol penuh atas seluruh sistem lintas kelas (di masa depan)
- Kemampuan menangani insiden teknis atau penyalahgunaan serius

**Fitur paling relevan:** Semua, plus akses konfigurasi sistem (multi-tenancy setup, dsb.)

**Hak akses:** Penuh — termasuk mengelola Class Admin, melihat data lintas kelas (dengan pertimbangan privasi), override moderasi.

*Catatan: role ini penting didefinisikan sejak awal di skema database (lihat prinsip Design for Scale), tapi secara UI di MVP bisa sangat minimal — cukup akses backend/admin panel sederhana.*

---

## 5. FUTURE USER — belum masuk MVP, tapi memengaruhi desain arsitektur

### Dosen
- **Kebutuhan potensial:** melihat progres/aktivitas kelas secara terbatas, mengumumkan info akademik langsung
- **Isu privasi:** dosen tidak boleh otomatis melihat konten sosial (feed, confession, gallery pribadi) — perlu role terpisah dengan visibility sangat terbatas
- **Implikasi desain sekarang:** field visibility harus punya opsi granular yang nanti bisa mengecualikan role "Dosen" tanpa refactor besar

### Alumni
- **Kebutuhan potensial:** akses arsip timeline & gallery setelah lulus, tetap terhubung dengan angkatan
- **Isu:** status keanggotaan berubah dari "Member aktif" ke "Alumni" — perlu konsep *membership status* (bukan hanya role tetap), karena hak akses alumni kemungkinan lebih terbatas (misal tidak bisa posting baru, hanya lihat arsip)
- **Implikasi desain sekarang:** tabel Membership harus punya field status (active/alumni/inactive), bukan cuma relasi user-class yang statis

### Pengurus Jurusan / Admin Kampus
- **Kebutuhan potensial:** melihat data agregat lintas kelas untuk keperluan administratif jurusan
- **Isu:** butuh layer "Organization" di atas "Class" — sudah diantisipasi lewat struktur multi-tenancy (Campus → Organization → Class → Membership → User)

### Kelas Lain (ekspansi horizontal)
- **Kebutuhan potensial:** kelas lain ingin memakai platform yang sama dengan datanya sendiri, terisolasi dari kelas lain
- **Implikasi desain sekarang:** setiap query & tabel data harus di-scope by `class_id` sejak awal, bukan ditambal belakangan — ini paling kritikal secara teknis karena retrofit multi-tenancy setelah data campur sangat mahal

---

## RINGKASAN IMPLIKASI DESAIN

| Persona | Dampak ke desain MVP sekarang |
|---|---|
| Member | Pusat dari semua fitur Fun First & Memory Forever |
| Class Admin | Butuh panel kelola sederhana untuk pengumuman, event, polling |
| Moderator | Butuh moderation queue sejak fitur sosial pertama diluncurkan (bukan setelahnya) |
| Super Admin | Cukup akses backend minimal di MVP, tapi role-nya harus ada di skema sejak awal |
| Dosen (future) | Visibility model harus granular per-role, bukan cuma public/class-only |
| Alumni (future) | Membership butuh field *status*, bukan relasi tetap |
| Jurusan/Kampus (future) | Struktur data harus punya layer Organization di atas Class |
| Kelas lain (future) | Semua data harus di-scope by `class_id` sejak baris kode pertama |

**Kesimpulan kunci:** User future tidak butuh fitur baru dibangun sekarang — mereka hanya butuh skema data & role tidak dihardcode untuk satu kelas. Ini konsisten dengan prinsip *Design for Scale, Build for One*.
