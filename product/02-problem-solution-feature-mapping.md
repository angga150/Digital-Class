# PROBLEM → SOLUTION → FEATURE MAPPING
## Digital Class Universe — MVP Prioritization

Prinsip: **SYSTEM BEFORE FEATURE**. Setiap fitur harus lolos uji: masalah nyata → solusi jelas → fitur konkret. Prioritas: **P0** wajib MVP, **P1** penting pasca-MVP, **P2** nice-to-have, **P3** eksperimen masa depan.

---

## KATEGORI A — INFORMASI TERSEBAR & TIDAK TERSTRUKTUR

### A1. Informasi kelas tersebar di WhatsApp/Telegram, mudah hilang ditelan chat
**Solution:** Satu sumber kebenaran (single source of truth) untuk info kelas, terpisah dari chat harian.
**Feature:** Class Dashboard + Academic Corner (jadwal, dosen, deadline, pengumuman)

| Kriteria | Detail |
|---|---|
| User yang butuh | Semua member, terutama saat cari info lama |
| Nilai | Tinggi — mengurangi "scroll WA cari info" |
| Frekuensi pakai | Harian |
| Dependensi | Butuh sistem user & role dulu |
| Kompleksitas | Sedang |
| Risiko | Adopsi rendah jika WA tetap jadi kebiasaan utama |
| **Prioritas** | **P0** |

### A2. Event/agenda sulit ditemukan, orang lupa tanggal acara
**Solution:** Sistem countdown terpusat dengan reminder visual.
**Feature:** Event Countdown System

| Kriteria | Detail |
|---|---|
| User | Semua member |
| Nilai | Tinggi — mengurangi miss event |
| Frekuensi | Harian (dilihat di dashboard) |
| Dependensi | Dashboard |
| Kompleksitas | Rendah–Sedang |
| Risiko | Rendah |
| **Prioritas** | **P0** |

### A3. Informasi akademik bercampur dengan obrolan santai
**Solution:** Pisahkan ruang formal (Academic Corner) dari ruang sosial (Feed).
**Feature:** Academic Corner (bukan LMS penuh — hanya info hub)

| Kriteria | Detail |
|---|---|
| User | Semua member |
| Nilai | Sedang-Tinggi |
| Frekuensi | Mingguan |
| Dependensi | - |
| Kompleksitas | Rendah |
| Risiko | Overengineering jadi LMS — harus dijaga scope-nya |
| **Prioritas** | **P0** (versi minimal: jadwal, dosen, deadline, pengumuman saja) |

---

## KATEGORI B — RELASI SOSIAL & IDENTITAS KELAS

### B1. Lupa ulang tahun teman sekelas
**Solution:** Sistem birthday dengan countdown otomatis dan privacy control.
**Feature:** Birthday System

| Kriteria | Detail |
|---|---|
| User | Semua member |
| Nilai | Tinggi — emosional, alasan orang buka web tiap hari |
| Frekuensi | Harian |
| Dependensi | User profile |
| Kompleksitas | Rendah |
| Risiko | Privacy tanggal lahir jika tidak dikontrol dengan baik |
| **Prioritas** | **P0** |

### B2. Anggota kelas tidak terlalu saling kenal
**Solution:** Profil personal yang casual (bukan CV) agar orang penasaran & saling explore.
**Feature:** Member Profile (bio, hobi, favorit, fun fact)

| Kriteria | Detail |
|---|---|
| User | Semua member |
| Nilai | Tinggi — fondasi identitas & personalisasi fitur lain |
| Frekuensi | Diisi sekali, dilihat sering |
| Dependensi | Auth system |
| Kompleksitas | Sedang |
| Risiko | Rendah, tapi perlu privacy setting per field |
| **Prioritas** | **P0** |

### B3. Tidak ada ruang berekspresi ringan sehari-hari
**Solution:** Feed ringan untuk share momen, bukan medsos penuh.
**Feature:** Social Feed (text/image post, reaction, comment)

| Kriteria | Detail |
|---|---|
| User | Semua member |
| Nilai | Tinggi — mesin engagement utama |
| Frekuensi | Harian |
| Dependensi | Profile, moderation |
| Kompleksitas | Sedang-Tinggi |
| Risiko | Bisa jadi ramai moderasi jika tidak dijaga |
| **Prioritas** | **P0** (versi minimal: post, like, comment) |

### B4. Tidak ada tempat aman menyuarakan hal sensitif/anonim
**Solution:** Confession anonim dengan moderasi & audit trail di backend.
**Feature:** Confession System

| Kriteria | Detail |
|---|---|
| User | Member (butuh moderator aktif) |
| Nilai | Sedang-Tinggi — engagement tinggi tapi butuh pengawasan |
| Frekuensi | Mingguan |
| Dependensi | Moderation system, role Moderator |
| Kompleksitas | Sedang (moderasi + anonymity backend) |
| Risiko | **Tinggi jika tanpa moderasi** — potensi bullying |
| **Prioritas** | **P1** (masuk MVP hanya jika moderation system sudah siap; jika tidak, tunda) |

---

## KATEGORI C — KEPUTUSAN & KOORDINASI KELOMPOK

### C1. Voting/polling masih manual di grup chat, sering tidak valid (orang vote 2x, hasil hilang ditelan chat)
**Solution:** Sistem polling terstruktur dengan validasi satu-vote-per-user.
**Feature:** Polling/Voting System

| Kriteria | Detail |
|---|---|
| User | Semua member, Class Admin sebagai pembuat |
| Nilai | Tinggi — kebutuhan rutin nyata |
| Frekuensi | Mingguan |
| Dependensi | Auth (identifikasi user unik) |
| Kompleksitas | Sedang |
| Risiko | Rendah |
| **Prioritas** | **P0** |

### C2. Kas kelas rawan tidak transparan
**Solution:** Pencatatan transaksi dengan role akses ketat.
**Feature:** Class Finance (kas, transaksi, laporan)

| Kriteria | Detail |
|---|---|
| User | Bendahara/Class Admin (input), Member (lihat) |
| Nilai | Tinggi jika kelas aktif kelola kas, rendah jika tidak |
| Frekuensi | Mingguan/bulanan |
| Dependensi | Role & permission matrix |
| Kompleksitas | Sedang-Tinggi (butuh audit trail, bukti transaksi) |
| Risiko | Tinggi jika data bisa diubah sembarangan — butuh kontrol akses ketat |
| **Prioritas** | **P1** — tunda ke pasca-MVP kecuali kelas eksplisit butuh dari awal |

---

## KATEGORI D — KENANGAN & ARSIP (MEMORY FOREVER)

### D1. Foto kelas tercecer di banyak HP/grup, hilang setelah lulus
**Solution:** Galeri terpusat dengan album & tag member.
**Feature:** Class Gallery

| Kriteria | Detail |
|---|---|
| User | Semua member |
| Nilai | Sangat tinggi — inti "memory forever" |
| Frekuensi | Mingguan (upload), harian (lihat) |
| Dependensi | Storage strategy (object storage direkomendasikan sejak awal) |
| Kompleksitas | Sedang-Tinggi (storage & moderasi gambar) |
| Risiko | Biaya storage jika tidak direncanakan; perlu moderasi konten |
| **Prioritas** | **P0** (versi dasar: upload + album, tanpa fitur lanjutan seperti face-tag) |

### D2. Tidak ada arsip perjalanan kelas dari semester ke semester
**Solution:** Timeline kronologis kelas yang bisa diisi bersama.
**Feature:** Class Timeline

| Kriteria | Detail |
|---|---|
| User | Semua member |
| Nilai | Sangat tinggi — fitur inti diferensiasi produk |
| Frekuensi | Bulanan (isi), sering (lihat) |
| Dependensi | Gallery, profile (tag member) |
| Kompleksitas | Sedang-Tinggi |
| Risiko | Butuh moderasi agar tidak jadi "sampah data" tak terkurasi |
| **Prioritas** | **P0**, tapi versi MVP disederhanakan: entri per tanggal + foto + cerita singkat, tanpa fitur kompleks (reaction/tag advanced masuk P1) |

### D3. Setelah lulus, kenangan bakal hilang begitu saja tanpa "penutup" yang berkesan
**Solution:** Pesan terkunci waktu untuk dibuka di masa depan.
**Feature:** Time Capsule

| Kriteria | Detail |
|---|---|
| User | Semua member |
| Nilai | Tinggi secara emosional, fitur signature/pembeda |
| Frekuensi | Ditulis sekali, dibuka sekali (event khusus) |
| Dependensi | Auth, timezone handling |
| Kompleksitas | Rendah-Sedang (logic-nya simpel: unlock_at check) |
| Risiko | Rendah, tapi harus dites soal timezone & keamanan (tidak boleh bisa dibuka manual sebelum waktunya) |
| **Prioritas** | **P1** — fitur bagus tapi tidak kritikal di hari-hari awal, cocok diluncurkan setelah engagement dasar terbentuk |

### D4. Tidak ada cara menghidupkan kembali momen lama secara organik
**Solution:** Resurfacing otomatis konten lama berdasarkan tanggal.
**Feature:** On This Day

| Kriteria | Detail |
|---|---|
| User | Semua member |
| Nilai | Sedang di awal, makin tinggi seiring data menua |
| Frekuensi | Harian (otomatis) |
| Dependensi | **Membutuhkan Timeline, Gallery, dan Feed sudah punya data historis** |
| Kompleksitas | Rendah (query berbasis tanggal) tapi percuma tanpa data lama |
| Risiko | Rendah, tapi useless jika diluncurkan terlalu dini (data kosong) |
| **Prioritas** | **P2** — secara teknis mudah, tapi nilainya nol sampai ada data minimal 1 tahun berjalan |

---

## KATEGORI E — ENGAGEMENT & KESERUAN (FUN FIRST)

### E1. Tidak ada apresiasi informal terhadap keunikan tiap orang
**Solution:** Penghargaan fun berbasis vote komunitas.
**Feature:** Hall of Fame

| Kriteria | Detail |
|---|---|
| User | Semua member |
| Nilai | Sedang-Tinggi, sangat "fun first" |
| Frekuensi | Musiman/per-semester |
| Dependensi | Voting system |
| Kompleksitas | Rendah-Sedang |
| Risiko | Kategori sensitif bisa mempermalukan — perlu kurasi kategori & approval |
| **Prioritas** | **P1** |

### E2. Tidak ada dorongan untuk terus aktif berkontribusi ke platform
**Solution:** Sistem lencana/achievement atas kontribusi positif.
**Feature:** Achievement System

| Kriteria | Detail |
|---|---|
| User | Semua member |
| Nilai | Sedang — bagus untuk retensi awal |
| Frekuensi | Trigger otomatis, dilihat sesekali |
| Dependensi | **Bergantung pada fitur lain sudah ada** (post, gallery, login) untuk trigger-nya |
| Kompleksitas | Sedang (butuh event-tracking system) |
| Risiko | Bisa terasa gimmicky jika berlebihan; hindari kompetitif |
| **Prioritas** | **P1** — versi minimal (3-5 badge dasar: First Login, Profile Complete, Historian) bisa masuk P0 karena murah dibangun & langsung terasa saat onboarding |

### E3. Kelas terasa monoton, tidak ada elemen ringan/hiburan
**Solution:** Kumpulan mini-fitur random untuk variasi interaksi.
**Feature:** Random/Fun Features (Random Member, Spin Wheel, Daily Question, dst.)

| Kriteria | Detail |
|---|---|
| User | Semua member |
| Nilai | Rendah-Sedang individual, tapi kumulatif bagus untuk stickiness |
| Frekuensi | Bervariasi |
| Dependensi | Profile, feed |
| Kompleksitas | Rendah per-fitur, tapi tiap fitur nambah maintenance |
| Risiko | Overengineering jika semua dibangun sekaligus |
| **Prioritas** | **P2-P3** — pilih 1-2 yang paling murah (mis. Daily Question) untuk MVP, sisanya eksperimen bertahap |

---

## KATEGORI F — KEAMANAN, PRIVASI & TATA KELOLA (fondasi wajib, bukan opsional)

### F1. Data sensitif (tanggal lahir, kontak, foto) berisiko bocor/disalahgunakan
**Solution:** Privacy model per-field (public/class-only/admin-only/private).
**Feature:** Privacy & Visibility Control

| Kriteria | Detail |
|---|---|
| User | Semua |
| Nilai | Kritikal — bukan pilihan |
| Frekuensi | Terus-menerus (enforced di setiap fitur) |
| Dependensi | Ini adalah *fondasi*, semua fitur lain bergantung padanya |
| Kompleksitas | Tinggi jika ditambal belakangan, rendah jika didesain dari awal |
| Risiko | **Sangat tinggi** jika diabaikan |
| **Prioritas** | **P0 — harus dibangun SEBELUM fitur sosial apa pun diluncurkan** |

### F2. Konten negatif (bullying, spam, konten tidak pantas) tidak terkendali
**Solution:** Moderation queue, report, block, hide, audit log.
**Feature:** Moderation System

| Kriteria | Detail |
|---|---|
| User | Moderator, Class Admin |
| Nilai | Kritikal — prasyarat Confession & Feed |
| Frekuensi | Terus-menerus |
| Dependensi | Role & permission |
| Kompleksitas | Sedang-Tinggi |
| Risiko | Tinggi jika absen — bisa merusak kepercayaan komunitas |
| **Prioritas** | **P0 — dibangun bersamaan dengan Feed/Confession, bukan setelahnya** |

### F3. Tidak jelas siapa boleh melakukan apa (admin vs member vs moderator)
**Solution:** Role & permission matrix yang tegas.
**Feature:** Role & Permission System (Super Admin, Class Admin, Moderator, Member, Guest)

| Kriteria | Detail |
|---|---|
| User | Semua, terutama Admin |
| Nilai | Kritikal — fondasi seluruh sistem |
| Frekuensi | Enforced terus-menerus |
| Dependensi | Tidak ada — ini fondasi paling dasar |
| Kompleksitas | Sedang |
| Risiko | Tinggi jika tidak ada sejak awal (technical debt besar) |
| **Prioritas** | **P0 — dibangun paling pertama, sebelum fitur apa pun** |

---

## RINGKASAN PRIORITAS MVP (P0)

Fondasi (dibangun lebih dulu, tidak terlihat user tapi wajib):
1. Role & Permission System
2. Privacy & Visibility Control (per-field)
3. Moderation System (dasar: report + queue)

Fitur user-facing MVP:
4. Class Dashboard
5. Academic Corner (minimal)
6. Event Countdown
7. Birthday System
8. Member Profile
9. Social Feed (dasar)
10. Polling/Voting
11. Class Gallery (dasar)
12. Class Timeline (dasar)
13. Achievement System (3-5 badge dasar saja)

## DITUNDA KE P1 (pasca-MVP)
Confession, Class Finance, Time Capsule, Hall of Fame, Achievement lanjutan, Timeline lanjutan (reaction/tag advanced)

## P2
On This Day, sebagian Random/Fun Features

## P3
Sisa Random/Fun Features sebagai eksperimen bertahap

---

**Catatan desain:** Tiga fondasi di Kategori F (Role, Privacy, Moderation) sengaja diletakkan sebagai prasyarat P0 meskipun tidak terlihat langsung oleh user — sesuai prinsip *system before feature*. Menunda fondasi ini demi mengejar fitur "seru" akan menciptakan utang teknis besar begitu Confession atau Feed mulai dipakai aktif.
