# 16 — Engineering Roadmap

Status: ✅ Final. Terjemahan teknis dari [`product/06-mvp-scope-and-roadmap.md`](../product/06-mvp-scope-and-roadmap.md) menjadi milestone development.

## Filosofi Urutan

Fondasi tidak terlihat user dibangun **lebih dulu**, bukan ditambal belakangan — konsisten dengan seluruh keputusan arsitektur sejauh ini. Fitur user-facing dibangun **di atas** fondasi tersebut, bukan paralel dengannya.

## Milestone 1 — Fondasi (Tidak Terlihat User)

- [ ] Setup Laravel project, konvensi module folder ([`02-architecture.md`](./02-architecture.md))
- [ ] Migration tabel inti: `users`, `classes`, `class_memberships`, `profiles` ([`03-database.md`](./03-database.md))
- [ ] Autentikasi: registrasi via join code, login, verifikasi email ([`05-authentication.md`](./05-authentication.md))
- [ ] Policy dasar per role ([`06-authorization.md`](./06-authorization.md))
- [ ] Visibility helper (`Visible::to`) dan Enum global
- [ ] Storage driver lokal + validasi upload ([`11-storage.md`](./11-storage.md))

**Definisi selesai Milestone 1:** user bisa daftar lewat join code, login, isi profil dengan kontrol visibility yang benar-benar berfungsi — meski belum ada satu pun fitur sosial.

## Milestone 2 — Fitur Inti Harian

- [ ] Class Dashboard (widget dasar) — [`07-features/dashboard.md`](./07-features/dashboard.md)
- [ ] Birthday System — [`07-features/birthdays.md`](./07-features/birthdays.md)
- [ ] Event Countdown — [`07-features/events.md`](./07-features/events.md)
- [ ] Academic Corner (versi minimal)
- [ ] Member Profile & daftar member — [`07-features/members.md`](./07-features/members.md)

**Definisi selesai Milestone 2:** ada alasan nyata bagi mahasiswa untuk membuka website setiap hari (*Fun First* mulai terasa), plus fungsi informasi dasar (*Useful Second*) sudah jalan.

## Milestone 3 — Interaksi Sosial (+ Moderasi Wajib Bersamaan)

- [ ] Moderation System (report, queue, audit log) — **dibangun bersamaan**, bukan setelah, Milestone ini — [`07-features/moderation.md`](./07-features/moderation.md)
- [ ] Social Feed — [`07-features/posts.md`](./07-features/posts.md)
- [ ] Polling — [`07-features/polls.md`](./07-features/polls.md)
- [ ] In-app Notification — [`07-features/notifications.md`](./07-features/notifications.md)

**Definisi selesai Milestone 3:** member bisa berinteraksi sosial (post, komentar, vote) dengan aman — ada jalur report & moderasi yang benar-benar berfungsi, bukan janji "akan ditambahkan nanti".

## Milestone 4 — Arsip & Kenangan

- [ ] Class Gallery — [`07-features/gallery.md`](./07-features/gallery.md)
- [ ] Class Timeline — [`07-features/memories.md`](./07-features/memories.md)
- [ ] Achievement System (3–5 badge dasar) — [`07-features/achievements.md`](./07-features/achievements.md)

**Definisi selesai Milestone 4:** seluruh scope P0 dari [`product/06-mvp-scope-and-roadmap.md`](../product/06-mvp-scope-and-roadmap.md) selesai. **Ini adalah MVP launch.**

## Milestone 5 — Pasca-MVP (P1, tidak dikerjakan sebelum Milestone 4 selesai)

Confession, Class Finance, Time Capsule, Hall of Fame, Achievement lanjutan — masing-masing punya rancangan awal di dokumen fitur terkait, dikerjakan satu per satu setelah MVP benar-benar dipakai kelas sungguhan dan ada masukan nyata dari pengguna, bukan diasumsikan sebelumnya.

## Yang Sengaja Tidak Ada di Roadmap Ini

On This Day (P2) dan sebagian besar Random/Fun Features (P2/P3) tidak dijadwalkan — nilainya bergantung pada data historis yang belum ada, atau butuh validasi engagement lebih dulu (lihat rasional di `product/02-problem-solution-feature-mapping.md`). Menjadwalkannya sekarang hanya akan jadi komitmen palsu.
