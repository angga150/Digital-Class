# Contributing Guide

Panduan kontribusi khusus untuk tim internal Digital Class Universe. Repository ini bersifat private/team repository, sehingga kita menggunakan alur kerja kolaborasi sederhana yang efisien.

## 1. Persiapan Awal

Pastikan Anda sudah mengonfigurasi Git di komputer lokal:
```bash
git config --global user.name "Nama Anda"
git config --global user.email "emailanda@example.com"
```

## 2. Git Branching Strategy

Jangan pernah melakukan *push* langsung ke branch `main`. Selalu gunakan branch terpisah untuk setiap task atau fitur yang Anda kerjakan.

- `main`: Branch utama untuk kode yang stabil dan siap rilis.
- `feature/nama-fitur`: Branch untuk membuat fitur baru (contoh: `feature/birthday-countdown`).
- `fix/nama-bug`: Branch untuk memperbaiki bug (contoh: `fix/poll-validation`).

## 3. Workflow Harian

1. Tarik perubahan terbaru dari branch `main`:
   ```bash
   git checkout main
   git pull origin main
   ```
2. Buat branch baru sesuai task Anda:
   ```bash
   git checkout -b feature/nama-fitur
   ```
3. Lakukan coding di dalam folder `src/`.
4. Periksa status perubahan:
   ```bash
   git status
   ```
5. Tambahkan file ke staging area:
   ```bash
   git add .
   ```
6. Lakukan commit dengan **Conventional Commits**:
   ```bash
   git commit -m "feat: add birthday countdown component"
   ```
   *Prefix yang didukung:*
   - `feat:` untuk penambahan fitur baru
   - `fix:` untuk perbaikan bug
   - `docs:` untuk perubahan dokumentasi
   - `refactor:` untuk merapikan kode tanpa mengubah fungsi
   - `test:` untuk menambah/mengubah pengujian
   - `chore:` untuk tugas pemeliharaan / konfigurasi

7. Dorong branch ke remote repository:
   ```bash
   git push origin feature/nama-fitur
   ```
8. Buka GitHub dan buat **Pull Request (PR)** dari branch Anda ke branch `main`.
9. Minta rekan setim untuk melakukan *code review* sebelum di-*merge*.

## 4. Perbedaan Peran (Backend vs Frontend)

Meskipun kita menggunakan Laravel + Livewire (monolitik), pembagian fokus tugas dapat dibagi secara fleksibel:

- **Backend Task:** Membuat migrasi database, Eloquent Model, relasi, validasi request, otorisasi (Policy), dan *query* Livewire logic.
- **Frontend Task:** Menyusun struktur Blade template, komponen UI Livewire, *styling* Tailwind CSS, desain responsif (mobile-first), serta penanganan *loading*, *empty*, dan *error state*.

Kolaborasikan keduanya dalam satu fitur yang utuh!
