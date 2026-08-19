# 01 — Product Summary (untuk konteks teknis)

Ini adalah ringkasan padat dari fase Product Design, ditulis untuk kebutuhan engineering. Untuk detail lengkap dan rasional keputusan, lihat dokumen di [`/product`](../product/).

## Apa Produk Ini

Digital Class Universe: ruang digital khusus kelas mahasiswa — kombinasi ringan dari Class Hub, Social Space, Digital Scrapbook, Class Archive, dan Information Hub. Bukan LMS, bukan aplikasi chat, bukan media sosial penuh.

## Prinsip yang Mengikat Keputusan Teknis

- **Fun First / Useful Second** — prioritas fitur mengikuti urutan ini, bukan sebaliknya
- **Memory Forever** — implikasi teknis: data historis (gallery, timeline) tidak boleh didesain sebagai data yang gampang hilang/di-purge
- **Community Owned** — implikasi teknis: sebagian besar write access ada di tangan Member, bukan hanya Admin
- **Privacy First** — implikasi teknis: visibility control harus granular per-field sejak skema database, bukan ditambal di layer aplikasi
- **Design for Scale, Build for One** — implikasi teknis: semua data di-scope `class_id` sejak baris kode pertama
- **System Before Feature** — implikasi teknis: fondasi (auth, role, privacy, moderation) dibangun sebelum fitur sosial apa pun
- **Simple First** — implikasi teknis: modular monolith, hindari microservices, hindari infra kompleks di MVP

## Scope MVP (ringkas)

**Fondasi (P0, dibangun lebih dulu):** Role & Permission, Privacy per-field, Moderation dasar

**Fitur user-facing (P0):** Class Dashboard, Academic Corner, Event Countdown, Birthday System, Member Profile, Social Feed, Polling, Class Gallery, Class Timeline, Achievement System dasar

**Ditunda ke P1+:** Confession, Class Finance, Time Capsule, Hall of Fame, On This Day

Rasional lengkap tiap fitur: [`product/02-problem-solution-feature-mapping.md`](../product/02-problem-solution-feature-mapping.md)

## Struktur Data Multi-Tenant (jangka panjang)

```
Campus → Faculty → Department → Class → Class Membership → User
```

MVP hanya mengisi level `Class` dan di bawahnya, tapi setiap tabel data di-scope `class_id` sejak awal. Detail: [`product/06-mvp-scope-and-roadmap.md`](../product/06-mvp-scope-and-roadmap.md)

## Role (level produk, akan diterjemahkan ke implementasi teknis di `06-authorization.md`)

Super Admin, Class Admin, Moderator, Member, Guest — permission matrix lengkap di [`product/04-roles-and-permissions.md`](../product/04-roles-and-permissions.md)
