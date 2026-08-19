# Digital Class Universe

> Ruang digital milik sebuah kelas yang menjadi pusat informasi, interaksi, identitas, keseruan, dan arsip kenangan mahasiswa selama masa perkuliahan hingga setelah lulus.

Digital Class Universe bukan sekadar website kelas formal (jadwal, struktur organisasi, pengumuman). Ini adalah *digital home* untuk kelas — kombinasi ringan dari Class Hub, Social Space, Digital Scrapbook, Class Archive, dan Information Hub.

## Status Proyek

🟢 **Perancangan lengkap.** Product design (`/product`) dan engineering design (`/docs`) sudah selesai — arsitektur, database, API, auth, authorization, spesifikasi tiap fitur, UI/UX, design system, security, storage, testing, deployment, dan git/team workflow semuanya sudah dirancang. **Belum ada kode diimplementasikan** — langkah berikutnya adalah eksekusi mengikuti [`docs/16-roadmap.md`](./docs/16-roadmap.md), dimulai dari Milestone 1 (fondasi: auth, membership, profile, visibility).

Proyek ini diperlakukan sebagai **real software project**: repo profesional, dokumentasi sinkron dengan kode, dan workflow tim yang jelas — bukan sekadar tugas kuliah.

## Prinsip Inti

Fun First · Useful Second · Memory Forever · Community Owned · Privacy First · Design for Scale, Build for One · System Before Feature · Simple First · Documentation as Source of Truth · Real User Value

Detail: [`product/01-vision-and-principles.md`](./product/01-vision-and-principles.md)

## Struktur Repo

```
.
├── README.md
├── CONTRIBUTING.md
├── CHANGELOG.md
├── LICENSE
├── .env.example
├── .gitignore
├── .github/                    # issue & PR templates, workflows CI (nanti)
│
├── product/                    # Fase 1: Product Design (selesai)
│   ├── 01-vision-and-principles.md
│   ├── 02-problem-solution-feature-mapping.md
│   ├── 03-target-user-analysis.md
│   ├── 04-roles-and-permissions.md
│   ├── 05-privacy-and-moderation.md
│   └── 06-mvp-scope-and-roadmap.md
│
├── docs/                       # Fase 2: Engineering & Development (selesai dirancang)
│   ├── 00-overview.md          # mulai baca dari sini
│   ├── 01-product.md .. 17-team-contribution.md   # semua ✅
│   └── 07-features/            # spesifikasi teknis per fitur
│
├── backend/                    # kosong — implementasi belum dimulai
└── frontend/                   # kosong — implementasi belum dimulai
```

## Mulai dari Mana?

- **Ingin paham produknya:** baca [`product/`](./product/), mulai dari `01-vision-and-principles.md`
- **Ingin paham rancangan teknis:** baca [`docs/00-overview.md`](./docs/00-overview.md) — peta lengkap semua dokumen
- **Ingin mulai implementasi:** baca [`docs/16-roadmap.md`](./docs/16-roadmap.md) untuk urutan milestone, lalu [`docs/15-development-workflow.md`](./docs/15-development-workflow.md) untuk setup & alur kerja harian
- **Ingin kontribusi:** baca [`CONTRIBUTING.md`](./CONTRIBUTING.md)

## Requirements, Installation, Database Setup, Testing, dll.

Belum dapat diisi — menunggu implementasi kode dimulai di `backend/`. Rancangan lengkapnya sudah ada: stack di [`docs/02-architecture.md`](./docs/02-architecture.md), skema database di [`docs/03-database.md`](./docs/03-database.md), setup lokal di [`docs/15-development-workflow.md`](./docs/15-development-workflow.md).

## Roadmap

Roadmap produk (P0–P3): [`product/06-mvp-scope-and-roadmap.md`](./product/06-mvp-scope-and-roadmap.md)
Roadmap engineering (4 milestone menuju MVP launch): [`docs/16-roadmap.md`](./docs/16-roadmap.md)

## License

[MIT](./LICENSE)
