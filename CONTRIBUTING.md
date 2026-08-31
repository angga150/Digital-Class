# Contributing

Panduan ini untuk anggota tim internal. Jadi dokumen ini tidak menjelaskan workflow open-source (fork, contributor asing, dll.) — cukup alur kerja tim kecil.

## Alur Kerja

```
Repository
  ↓
Anggota tim diundang sebagai collaborator
  ↓
Tiap orang kerja di branch masing-masing
  ↓
Push branch
  ↓
Pull Request
  ↓
Review oleh anggota tim lain
  ↓
Merge ke master
```

**Jangan push langsung ke `master`.** Semua perubahan lewat Pull Request, meski perubahannya kecil — supaya ada jejak review dan tidak ada yang tidak sengaja merusak kode orang lain.

## Langkah Konkret

```bash
git pull origin master
git checkout -b feature/birthday-countdown

# ...coding...

git add .
git commit -m "feat: tambah countdown ulang tahun di dashboard"
git push origin feature/birthday-countdown
```

Setelah push, buka Pull Request di GitHub, minta review minimal 1 anggota tim lain, merge setelah disetujui.

## Kalau Satu Fitur Dikerjakan 2 Orang (Backend & Frontend) di Branch yang Sama

Karena stack kita Laravel + Livewire, satu fitur biasanya melewati backend dan frontend sekaligus dalam satu alur (lihat [`docs/03-architecture.md`](./docs/03-architecture.md) §4) — jadi wajar kalau dua orang kerja di **branch yang sama**, bukan branch backend/frontend terpisah.

**Yang perlu dipahami: nama branch yang sama di dua komputer BUKAN otomatis satu kesatuan kerja.** Git tidak tahu maksud kalian — yang dia tahu cuma histori commit. Kalau dua orang bikin branch dengan nama sama secara terpisah tanpa saling `pull` dulu, push salah satu akan **ditolak**, atau kalau dipaksa (`--force`) bisa **menimpa dan menghilangkan kerjaan satu sama lain**.

### Cara yang Benar: Satu Orang Mulai Duluan, yang Lain Menyusul di Branch yang Sama

```bash
# Orang pertama (mulai duluan, misal bagian backend)
git checkout master
git pull origin master
git checkout -b feature/birthday-countdown

# ...kerja migration, model, Livewire logic...
git add .
git commit -m "feat: birthday model & Livewire logic"
git push origin feature/birthday-countdown
# branch ini sekarang resmi ada di GitHub (remote)
```

```bash
# Orang kedua (menyusul, misal bagian frontend)
git checkout master
git pull origin master
git fetch origin
git checkout feature/birthday-countdown
# PENTING: tanpa -b, karena branch-nya SUDAH ADA di remote, bukan bikin baru
git pull origin feature/birthday-countdown
# pastikan dapat commit terbaru dari orang pertama sebelum mulai kerja

# ...kerja Blade, styling...
git add .
git commit -m "feat: birthday countdown UI"
git push origin feature/birthday-countdown
```

**Kunci utamanya:** `git checkout <nama-branch>` (tanpa `-b`) untuk mengambil branch yang sudah ada, bukan `git checkout -b <nama-branch>` yang bikin baru. Kalau branch belum ada di remote, Git akan kasih error jelas — itu tandanya kamu perlu koordinasi dulu sebelum lanjut, bukan asal jalan bikin branch sendiri.

### Kalau Terlanjur Dua Orang Bikin Branch Sama Tanpa Saling Tahu

Push kedua akan ditolak dengan pesan semacam `rejected — non-fast-forward`. Solusinya, orang kedua tarik dulu perubahan dari remote sebelum push:

```bash
git pull origin feature/birthday-countdown --rebase
git push origin feature/birthday-countdown
```

Kalau kalian berdua kebetulan mengubah file yang sama persis di baris yang sama, baru muncul **merge conflict** sungguhan — Git akan menandai bagian yang bentrok di file terkait, tinggal dipilih/digabung manual isinya, lalu commit ulang. Kalau kalian kerja di file berbeda (misal satu di `app/Livewire/Birthday.php`, satu di `resources/views/livewire/birthday.blade.php`), biasanya otomatis tergabung tanpa konflik.

### Supaya Situasi Ini Jarang Terjadi

Sebelum mulai kerja fitur yang bakal dikerjakan 2 orang, kabari dulu di grup tim ("aku mulai kerjain birthday countdown bagian backend ya, nanti nyusul siapa buat UI-nya?") — bukan aturan Git tambahan, cukup kebiasaan komunikasi singkat sebelum bikin branch.

## Konvensi Penamaan Branch

`<type>/<deskripsi-singkat>` — contoh: `feature/birthday-countdown`, `fix/poll-double-vote`, `docs/update-database`.

## Konvensi Commit

Pakai prefix sederhana di awal commit message:

| Prefix      | Kapan dipakai                        |
| ----------- | ------------------------------------ |
| `feat:`     | Fitur baru                           |
| `fix:`      | Perbaikan bug                        |
| `docs:`     | Perubahan dokumentasi saja           |
| `refactor:` | Rapikan kode tanpa mengubah perilaku |
| `test:`     | Menambah/mengubah test               |
| `chore:`    | Setup, dependency, config            |

Contoh: `feat: tambah widget birthday di dashboard`, `fix: cegah user vote dua kali di polling`

Tidak perlu aturan Git yang lebih rumit dari ini — cukup konsisten.

## Pembagian Fokus Backend & Frontend

Karena stack kita Laravel + Livewire, satu fitur biasanya melewati backend dan frontend sekaligus (lihat [`docs/03-architecture.md`](./docs/03-architecture.md) §4). Pembagian di bawah ini adalah **panduan fokus**, bukan aturan kaku — kamu boleh dan wajar mengerjakan keduanya untuk fitur yang tidak terlalu besar.

**Task condong backend:**
migration → model → relationship → validation → authorization → Livewire logic → test

**Task condong frontend:**
Blade → Livewire UI → Tailwind → responsive → loading/error/empty state

## Sebelum Membuka Pull Request

```
[ ] Kode sudah dicoba jalan di lokal
[ ] Sudah dicek manual dengan minimal 2 role berbeda (misal Member & Class Admin) kalau fitur ini punya aturan permission
[ ] Dokumentasi terkait (docs/features/... atau lainnya) sudah diupdate kalau ada perubahan perilaku/struktur
[ ] Checklist security di docs/03-architecture.md §7 sudah dicek
```

## Cara Mengerjakan Fitur

Urutan lengkap ada di [`docs/02-development.md`](./docs/02-development.md) §9 "How to Create a Feature" — mulai dari baca dokumentasi fitur sampai buka Pull Request.

## Kalau Ada Perbedaan Pendapat Teknis

Diskusikan di issue (bukan di komentar PR yang mudah tenggelam), putuskan bersama tim. Kalau perubahan menyangkut struktur database atau keputusan yang sudah tertulis di dokumentasi, update juga dokumentasinya dalam PR yang sama — jangan biarkan dokumentasi jadi usang.
