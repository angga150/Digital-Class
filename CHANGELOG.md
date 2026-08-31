# Changelog

Semua perubahan signifikan pada proyek ini didokumentasikan di sini.

## [Unreleased]

### Changed
- **Rewrite dokumentasi menyeluruh.** Struktur lama (`product/` 6 file + `docs/` 17 file + `docs/07-features/` 12 file = 35 dokumen) disederhanakan menjadi 5 dokumen utama (`docs/01-product.md` s.d. `05-deployment.md`) + `docs/features/` (spesifikasi per fitur). Tidak ada informasi yang dihapus — hanya digabung dan disusun ulang supaya lebih mudah diikuti programmer pemula. Lihat isi tiap dokumen baru untuk detail lengkap.
- Struktur repository diubah: source code Laravel sekarang berada di `src/`, bukan di root repository.
- Keputusan teknologi (Laravel + Livewire + Tailwind + MySQL) ditegaskan sebagai final — dokumentasi tidak lagi menampilkan opsi teknologi alternatif yang tidak dipakai.

### Added
- Dokumentasi produk, development guide, architecture, database, dan deployment lengkap.
- Spesifikasi teknis per fitur di `docs/features/`.
- Template issue & Pull Request untuk tim internal.

### Pending
- Implementasi kode di `src/` — belum dimulai.
