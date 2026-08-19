# 17 — Team Contribution Guide

Status: ✅ Final.

## Peran dalam Tim (Konteks Proyek Tugas Kelompok)

Tidak semua istilah peran engineering formal (Tech Lead, dsb.) perlu dipaksakan ke tim kecil. Cukup pembagian tanggung jawab per **domain module** (lihat [`02-architecture.md`](./02-architecture.md) §3), agar setiap anggota punya area yang jelas dimiliki:

| Domain Module | Mencakup Fitur |
|---|---|
| Membership & Auth | Login, registrasi, profil, role |
| Dashboard & Birthday/Event | Dashboard, birthday system, event countdown |
| Feed & Poll | Social feed, polling |
| Gallery & Timeline | Gallery, class timeline |
| Achievement & Notification | Achievement system, in-app notification |
| Moderation & Core | Privacy/visibility, moderation, audit log — **lintas module**, butuh koordinasi paling ketat karena dipakai semua orang |

Satu orang bisa pegang lebih dari satu module tergantung ukuran tim; yang penting setiap module punya minimal satu "pemilik" yang paham penuh area itu.

## Alur Kerja Harian

1. Ambil task dari issue tracker (GitHub Issues), pastikan sudah merujuk fitur di [`07-features/`](./07-features/) atau bagian produk terkait.
2. Buat branch sesuai konvensi [`14-git-workflow.md`](./14-git-workflow.md).
3. Kerjakan perubahan **kode + dokumentasi terkait dalam PR yang sama** — bukan "nanti didokumentasikan belakangan".
4. Ajukan PR, isi template, minta review.
5. Setelah disetujui, merge sendiri (bukan menunggu maintainer tunggal — tim kecil, semua kontributor punya akses merge setelah approval).

## Standar Kode Minimal

- Ikuti [Laravel Pint](https://laravel.com/docs/pint) (formatter bawaan Laravel) — jangan berdebat soal gaya kode, biarkan tool yang menentukan.
- Nama variabel/fungsi dalam **Bahasa Inggris** (konsisten dengan konvensi Laravel/PHP umum), komentar/dokumentasi boleh Bahasa Indonesia.
- Setiap domain module punya folder sendiri (lihat `02-architecture.md`) — jangan taruh logic module lain di controller yang salah hanya karena "lebih cepat".

## Onboarding Anggota Baru / Pergantian Tim per Semester

Karena tim mahasiswa realistis akan berganti-ganti tiap semester, urutan baca wajib untuk anggota baru:

1. [`product/01-vision-and-principles.md`](../product/01-vision-and-principles.md) — kenapa proyek ini ada
2. [`docs/00-overview.md`](./00-overview.md) — peta status semua dokumen
3. [`docs/02-architecture.md`](./02-architecture.md) — stack & cara kode diorganisasi
4. [`docs/07-features/`](./07-features/) — fitur yang akan dikerjakan
5. Dokumen ini — cara berkontribusi

## Menangani Keputusan yang Berubah

Jika suatu keputusan di dokumentasi (mis. skema database, pilihan fitur) perlu diubah setelah diimplementasi, jangan diam-diam mengubah kode saja — buat PR yang eksplisit mengubah dokumen terkait DAN kode-nya, dengan alasan perubahan ditulis di deskripsi PR. Ini menjaga dokumentasi tetap jadi *source of truth* yang bisa dipercaya, bukan arsip usang.

## Konflik & Keputusan Teknis

Jika ada perbedaan pendapat teknis yang tidak selesai lewat diskusi PR biasa, eskalasi ke issue terpisah berlabel `discussion`, putuskan lewat konsensus tim dalam waktu terbatas (mis. 1 minggu) — jangan biarkan keputusan menggantung tanpa batas waktu.
