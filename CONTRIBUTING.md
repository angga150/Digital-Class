# Contributing to Digital Class Universe

Proyek ini diperlakukan sebagai **real software project**, bukan tugas kuliah biasa. Kontribusi (termasuk dari anggota tim sendiri) mengikuti alur di bawah ini.

Alur kontribusi lengkap (branching, commit convention, PR, onboarding) ada di [`docs/14-git-workflow.md`](./docs/14-git-workflow.md) dan [`docs/17-team-contribution.md`](./docs/17-team-contribution.md) — dokumen ini hanya ringkasan cepat.

## Prinsip

1. **Documentation as Source of Truth** — perubahan kode yang mengubah perilaku/struktur wajib disertai update dokumentasi terkait di `/docs`, dalam PR yang sama.
2. **System Before Feature** — jangan menambah fitur baru sebelum fondasi yang dibutuhkan (auth, role, privacy, moderation) sudah ada.
3. Baca dulu dokumen di [`/product`](./product/) sebelum mengusulkan fitur baru — jika suatu ide belum ada di sana, diskusikan dulu lewat issue sebelum coding.

## Alur Kontribusi

Lihat detail lengkap di [`docs/14-git-workflow.md`](./docs/14-git-workflow.md) (branching, commit convention, PR) dan [`docs/17-team-contribution.md`](./docs/17-team-contribution.md) (pembagian domain module, onboarding, standar kode). Ringkasan singkat:

1. Buat issue yang menjelaskan masalah/fitur yang ingin dikerjakan
2. Diskusikan scope-nya — cek apakah sudah ada rasionalnya di `/product/02-problem-solution-feature-mapping.md`
3. Buat branch dari `master` sesuai konvensi `feature/`, `fix/`, `docs/`, `chore/`
4. Kerjakan perubahan kode + dokumentasi terkait dalam PR yang sama
5. Ajukan pull request, minta review minimal 1 anggota tim lain
6. Merge setelah disetujui

## Pertanyaan?

Buka issue dengan label `question`.
