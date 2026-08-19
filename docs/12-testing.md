# 12 — Testing Strategy

Status: ✅ Final untuk MVP.

## Prinsip: Test yang Bernilai, Bukan Coverage 100%

Tim mahasiswa dengan waktu terbatas — testing diprioritaskan pada **logic berisiko tinggi**, bukan semua baris kode. Ini konsisten dengan *Simple First*: testing yang berlebihan di area low-risk membuang waktu yang lebih berharga dipakai membangun fitur.

## Yang WAJIB Ditest (Feature/Unit Test)

Area di mana bug berarti kebocoran data privat, manipulasi hasil, atau kerusakan permanen — bukan sekadar tampilan salah:

| Area | Kenapa kritikal |
|---|---|
| `class_id` scoping | Bug di sini = data satu kelas bocor ke kelas lain (jika multi-kelas aktif nanti) — paling berbahaya |
| Visibility enforcement (`06-authorization.md`) | Bug di sini = data privat (birthday, dsb.) terekspos ke yang tidak berhak |
| Vote/reaction unique constraint | Bug di sini = hasil polling bisa dimanipulasi |
| Role/permission Policy | Bug di sini = member biasa bisa melakukan aksi Class Admin |
| Achievement trigger | Bug di sini = award duplikat atau tidak pernah ter-trigger |

## Yang TIDAK Wajib Ditest Detail di MVP

- Tampilan visual (styling Tailwind, layout) — dicek manual saat development, bukan automated visual regression test (tooling-nya sendiri sudah overkill untuk skala ini).
- CRUD sederhana tanpa business logic kompleks (mis. update bio profil) — cukup ditest jika ada waktu, bukan prioritas.

## Tooling

- **PHPUnit / Pest** (bawaan Laravel) untuk Feature Test & Unit Test.
- Database testing memakai `RefreshDatabase` trait dengan SQLite in-memory (lebih cepat dari MySQL asli untuk test suite) — kecuali ada fitur yang bergantung fitur spesifik MySQL, baru pakai MySQL test database.s
- Tidak memakai browser testing (Dusk) di MVP — Livewire component testing bawaan Laravel (`Livewire::test(...)`) sudah cukup untuk menguji interaksi tanpa overhead browser otomatis.

## Continuous Integration (CI)

GitHub Actions dengan satu workflow sederhana: jalan test suite pada setiap PR ke `master`. Tidak perlu multi-stage pipeline kompleks (build/test/deploy terpisah) — cukup satu job `test` yang harus hijau sebelum merge (lihat [`14-git-workflow.md`](./14-git-workflow.md)).

```yaml
# .github/workflows/tests.yml (ringkasan konsep, bukan file final)
on: pull_request
jobs:
  test:
    steps:
      - checkout
      - setup PHP + composer install
      - php artisan test
```

## Manual Testing Checklist (untuk fitur tanpa automated test)

Sebelum merge, kontributor mengecek manual: apakah fitur berjalan sesuai user flow di `07-features/{fitur}.md`, dan apakah error state (validasi gagal, data kosong) sudah ditangani — dicatat di deskripsi PR, bukan formalitas checklist terpisah yang menambah friksi.
