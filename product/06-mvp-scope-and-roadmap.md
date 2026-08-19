# MVP SCOPE & ROADMAP
## Digital Class Universe

## Prinsip: Design for Scale, Build for One

MVP dibangun **untuk satu kelas**, tapi skema data & arsitektur tidak boleh hardcode ke satu kelas. Struktur jenjang yang diantisipasi sejak awal:

```
Campus
  └─ Organization (jurusan/fakultas)
       └─ Class
            └─ Membership (relasi user–class, punya role & status)
                 └─ User
```

Implikasi konkret di level kode: setiap tabel data (post, gallery, timeline, dsb.) di-scope dengan `class_id` sejak baris kode pertama, bukan ditambahkan belakangan. Retrofit multi-tenancy setelah data bercampur sangat mahal.

## Scope MVP (P0)

**Fondasi (dibangun lebih dulu, prasyarat fitur lain):**
- Role & Permission System
- Privacy & Visibility Control (per-field)
- Moderation System (dasar: report + queue)

**Fitur user-facing:**
- Class Dashboard
- Academic Corner (versi minimal: jadwal, dosen, deadline, pengumuman)
- Event Countdown
- Birthday System
- Member Profile
- Social Feed (post, like, comment — versi dasar)
- Polling/Voting
- Class Gallery (upload + album, versi dasar)
- Class Timeline (entri tanggal + foto + cerita singkat, versi dasar)
- Achievement System (3–5 badge dasar: First Login, Profile Complete, Historian)

## Pasca-MVP (P1)

- Confession (menunggu Moderation System matang)
- Class Finance
- Time Capsule
- Hall of Fame
- Achievement lanjutan (badge kompleks, trigger otomatis lebih kaya)
- Timeline lanjutan (reaction, tag member advanced)

## Nice-to-Have (P2)

- On This Day (butuh minimal ~1 tahun data historis agar bernilai)
- Sebagian Random/Fun Features (mis. Daily Question)

## Eksperimen Masa Depan (P3)

- Sisa Random/Fun Features (Spin Wheel, Meme Generator, Anime Character Voting, dll.) — diuji satu per satu, bukan sekaligus

## Rasional Detail

Rasional lengkap tiap fitur (problem → solution → feature, kriteria evaluasi, dan alasan prioritas) ada di [`02-problem-solution-feature-mapping.md`](./02-problem-solution-feature-mapping.md).

## Jalur Pertumbuhan Jangka Panjang (bukan MVP, hanya arah)

1. **Satu kelas** *(MVP saat ini)*
2. **Beberapa kelas dalam satu angkatan** — butuh dashboard lintas kelas untuk Super Admin, tapi data tetap terisolasi per `class_id`
3. **Satu angkatan/jurusan** — layer Organization mulai relevan
4. **Satu fakultas/kampus** — layer Campus mulai relevan, kebutuhan role Dosen & Admin Kampus mulai konkret
5. **Lintas kampus** — full multi-tenant, kemungkinan butuh model bisnis/hosting terpisah per kampus

Setiap tahap di atas **tidak dikerjakan sekarang**. Yang dikerjakan sekarang hanya memastikan tahap 1 tidak menutup jalan ke tahap-tahap berikutnya.
