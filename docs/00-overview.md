# 00 — Overview

Dokumen ini adalah peta navigasi seluruh dokumentasi Digital Class Universe. Dokumentasi dibagi menjadi dua fase.

## Fase 1 — Product Design (`/product`)

Selesai. Berisi keputusan visi, prinsip, problem-solution-feature mapping, target user, role & permission, privacy model, dan scope MVP.

| Dokumen | Isi |
|---|---|
| [`product/01-vision-and-principles.md`](../product/01-vision-and-principles.md) | Visi & 6 prinsip produk |
| [`product/02-problem-solution-feature-mapping.md`](../product/02-problem-solution-feature-mapping.md) | Setiap fitur dipetakan dari masalah nyata + prioritas P0–P3 |
| [`product/03-target-user-analysis.md`](../product/03-target-user-analysis.md) | Persona & kebutuhan tiap role |
| [`product/04-roles-and-permissions.md`](../product/04-roles-and-permissions.md) | Permission matrix |
| [`product/05-privacy-and-moderation.md`](../product/05-privacy-and-moderation.md) | Model visibility & moderasi |
| [`product/06-mvp-scope-and-roadmap.md`](../product/06-mvp-scope-and-roadmap.md) | Scope MVP & jalur pertumbuhan |

## Fase 2 — Engineering & Development (`/docs`, dokumen ini)

**Perancangan selesai.** Semua dokumen di bawah adalah hasil rancangan (arsitektur, database, API, auth, workflow, testing, deployment) — belum ada implementasi kode. Implementasi (Milestone 1–4, lihat `16-roadmap.md`) adalah langkah selanjutnya, terpisah dari fase perancangan ini.

| Dokumen | Isi | Status |
|---|---|---|
| [`01-product.md`](./01-product.md) | Ringkasan produk untuk konteks teknis | ✅ |
| [`02-architecture.md`](./02-architecture.md) | Technical stack & architecture diagram | ✅ Laravel + Blade/Livewire + MySQL, modular monolith |
| [`03-database.md`](./03-database.md) | Skema database | ✅ Skema MVP + ERD; Confession/Finance/Time Capsule sengaja ditunda |
| [`04-api.md`](./04-api.md) | Desain API | ✅ Struktur endpoint per module, Sanctum untuk kebutuhan future |
| [`05-authentication.md`](./05-authentication.md) | Sistem autentikasi | ✅ Session-based (Breeze), registrasi via join code kelas |
| [`06-authorization.md`](./06-authorization.md) | Sistem otorisasi (role & permission teknis) | ✅ Laravel Policy berbasis `class_memberships.role` + status |
| [`07-features/`](./07-features/) | Spesifikasi teknis per fitur | ✅ 11 fitur P0 selesai; Time Capsule (P1) sengaja ditunda |
| [`08-ui-ux.md`](./08-ui-ux.md) | Fondasi UI/UX | ✅ Prinsip UX, navigasi, layout pattern, mobile-first |
| [`09-design-system.md`](./09-design-system.md) | Design system | ✅ Token minimal, komponen Blade wajib, Heroicons |
| [`10-security.md`](./10-security.md) | Strategi keamanan | ✅ Input validation, mass assignment, rate limit, backup |
| [`11-storage.md`](./11-storage.md) | Strategi penyimpanan media | ✅ Local disk → object storage, validasi upload, retensi |
| [`12-testing.md`](./12-testing.md) | Strategi testing | ✅ Fokus area kritikal (scoping, visibility, vote), bukan 100% coverage |
| [`13-deployment.md`](./13-deployment.md) | Strategi deployment | ✅ VPS/PaaS sederhana, deploy manual di MVP |
| [`14-git-workflow.md`](./14-git-workflow.md) | Alur kerja Git | ✅ Trunk-based sederhana, conventional commits |
| [`15-development-workflow.md`](./15-development-workflow.md) | Alur kerja development harian | ✅ Setup lokal, siklus kerja, Definition of Done |
| [`16-roadmap.md`](./16-roadmap.md) | Roadmap engineering | ✅ 4 milestone menuju MVP launch + P1 |
| [`17-team-contribution.md`](./17-team-contribution.md) | Panduan kontribusi tim | ✅ Pembagian domain module, onboarding, standar kode |

## Prinsip Dokumentasi

> **Documentation as Source of Truth** — dokumentasi dan codebase harus selalu sinkron. Perubahan arsitektur atau desain yang tidak diikuti perubahan dokumentasi dianggap belum selesai.

## Status Keseluruhan

**Seluruh perancangan (product design + engineering design) sudah selesai.** Tidak ada lagi dokumen berstatus 🔴/🟡. Langkah berikutnya adalah **implementasi**, mengikuti urutan Milestone 1–4 di [`16-roadmap.md`](./16-roadmap.md) — dimulai dari Milestone 1 (fondasi: auth, membership, profile, visibility), bukan langsung fitur yang paling menarik.

Jika di tengah implementasi ada keputusan di dokumen ini yang perlu diubah, ikuti prosedur di [`17-team-contribution.md`](./17-team-contribution.md) §"Menangani Keputusan yang Berubah" — jangan diam-diam menyimpang dari dokumen tanpa mengupdatenya.
