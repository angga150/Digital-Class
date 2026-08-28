# Contributing Guide

Panduan ini dibuat untuk **tim internal** Digital Class Universe, bukan untuk workflow open-source.

## 1. Sebelum Mulai

Baca:

1. [`README.md`](README.md)
2. [`docs/02-development.md`](docs/02-development.md)
3. Dokumentasi fitur yang sedang dikerjakan di [`docs/features/`](docs/features/)

Jika task menyentuh database atau permission, baca bagian terkait di:

- [`docs/04-database.md`](docs/04-database.md)
- [`docs/03-architecture.md`](docs/03-architecture.md)

## 2. Workflow Utama

```text
Ambil issue
   ↓
Baca feature documentation
   ↓
Buat branch
   ↓
Coding
   ↓
Test
   ↓
Update dokumentasi jika perlu
   ↓
Pull Request
   ↓
Review minimal 1 anggota
   ↓
Merge ke main
```

**Jangan push langsung ke `main`.**

## 3. Branch

Gunakan format:

```text
feature/<deskripsi>
fix/<deskripsi>
docs/<deskripsi>
chore/<deskripsi>
```

Contoh:

```bash
git pull origin main
git checkout -b feature/birthday-countdown
```

## 4. Commit

Gunakan prefix sederhana:

```text
feat: tambah birthday countdown
fix: cegah double vote pada polling
docs: update database documentation
refactor: rapikan query dashboard
test: tambah test polling
chore: update dependency
```

## 5. Pull Request

Setiap perubahan masuk melalui PR.

Minimal:

- Jelaskan apa yang berubah.
- Jelaskan kenapa perubahan diperlukan.
- Hubungkan issue jika ada.
- Pastikan test relevan sudah dijalankan.
- Pastikan permission dan visibility tidak rusak.
- Update dokumentasi jika keputusan/flow berubah.
- Minta review minimal satu anggota tim lain.

## 6. Pembagian Backend dan Frontend

### Backend

Fokus utama:

- migration
- model dan relationship
- business logic
- validation
- authorization / Policy
- Livewire action
- testing
- database

### Frontend

Fokus utama:

- Blade
- Livewire UI
- Tailwind CSS
- responsive/mobile-first UI
- form UI
- loading state
- empty state
- error state

Pembagian ini **tidak kaku**. Developer boleh mengerjakan end-to-end sebuah feature jika diperlukan.

## 7. Standar Kode

- Ikuti Laravel convention terlebih dahulu.
- Gunakan Laravel Pint.
- Nama class, method, variable, dan database identifier menggunakan Bahasa Inggris.
- Komentar dan dokumentasi boleh Bahasa Indonesia.
- Jangan membuat abstraction hanya supaya terlihat "enterprise".
- Gunakan Model, Policy, Livewire, Blade, dan Service sederhana bila memang diperlukan.
- Setiap feature harus tetap ter-scope dengan `class_id`.

## 8. Cara Membuat Feature

1. Baca `docs/features/<feature>.md`.
2. Tentukan perubahan data.
3. Buat/ubah migration.
4. Buat/ubah Model dan relationship.
5. Buat Policy/authorization jika diperlukan.
6. Buat Livewire component.
7. Implement business logic.
8. Tambahkan validation.
9. Buat UI Blade.
10. Styling dengan Tailwind.
11. Handle loading, empty, dan error state.
12. Tambahkan test untuk logic kritikal.
13. Test manual.
14. Commit.
15. Push branch.
16. Buka Pull Request.

Urutan ini adalah pola kerja, bukan aturan yang harus diikuti secara kaku pada setiap task.

## 9. Definition of Done

Task dianggap selesai jika:

- [ ] User flow feature berjalan.
- [ ] Business rules sudah ditangani.
- [ ] Edge case penting sudah dipikirkan.
- [ ] Permission/visibility benar.
- [ ] Test kritikal tersedia jika relevan.
- [ ] Dokumentasi terkait sudah diperbarui.
- [ ] PR direview minimal satu anggota.
- [ ] Tidak ada secret di repository.

## 10. Sebelum PR: Security Checklist

- [ ] Input sudah divalidasi di server.
- [ ] Authorization sudah dicek.
- [ ] File upload divalidasi.
- [ ] Tidak ada secret di repository.
- [ ] Data private tidak bocor melalui UI/query.
- [ ] `class_id` scope tidak bisa dilewati dengan mengubah parameter/request.

## 11. Jika Keputusan Berubah

Jangan mengubah kode diam-diam jika perubahan menyentuh:

- schema database,
- permission matrix,
- business rules,
- scope MVP,
- architecture.

Ubah dokumentasi dan kode dalam PR yang sama. Jika perbedaan pendapat tidak selesai, gunakan issue berlabel `discussion` dan putuskan dalam waktu terbatas.

## 12. Onboarding Anggota Baru

Developer baru cukup mengikuti:

```text
README
  ↓
02-development
  ↓
03-architecture
  ↓
feature yang dikerjakan
  ↓
coding
```

Tidak perlu membaca seluruh repository sebelum mulai bekerja.
