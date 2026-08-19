# 08 — UI/UX Foundation

Status: ✅ Final untuk MVP.

## Prinsip UX yang Mengikat Keputusan Desain

Diturunkan langsung dari prinsip produk ([`product/01-vision-and-principles.md`](../product/01-vision-and-principles.md)):

| Prinsip Produk | Implikasi UI/UX |
|---|---|
| Fun First | Warna, ilustrasi, microcopy terasa hangat/personal — bukan tone korporat/formal. Emoji dipakai wajar di label (mis. "🎂 Birthday berikutnya"), bukan dihindari demi "profesionalisme" |
| Useful Second | Informasi fungsional (jadwal, deadline) tetap mudah ditemukan meski bukan elemen paling mencolok — biasanya ada di Academic Corner, bukan dashboard utama |
| Memory Forever | Gallery & Timeline dirancang untuk *dijelajahi berlama-lama* (grid visual besar, bukan list padat) — beda mood dari halaman Academic Corner yang ringkas |
| Community Owned | Konten dari user (avatar, post, foto) mendominasi visual, bukan elemen branding admin |
| Privacy First | Kontrol visibility harus terlihat jelas saat mengisi data sensitif (mis. toggle visibility langsung di sebelah input birthday), bukan tersembunyi di menu pengaturan terpisah yang jarang dibuka |

## Navigasi Utama

Struktur navigasi datar (flat), maksimal 2 level — tim mahasiswa dan pengguna (mahasiswa juga) sama-sama tidak familiar dengan aplikasi kompleks bernavigasi dalam:

```
Dashboard | Feed | Gallery | Timeline | Academic | Members
                                                       └─ Profil Saya
```

Notification bell dan avatar/menu akun ada di top bar, konsisten di semua halaman (bukan per-halaman berbeda).

## Layout Pattern per Jenis Halaman

| Jenis Konten | Pattern |
|---|---|
| Dashboard | Grid widget (card-based), responsif jadi 1 kolom di mobile |
| Feed | Single column, infinite scroll |
| Gallery | Grid foto (masonry/uniform grid), modal lightbox saat foto diklik |
| Timeline | Vertical timeline, grouped by tahun |
| Academic Corner | Tabel/list ringkas, prioritas kepadatan informasi di atas estetika |
| Form (post/event/poll baru) | Modal atau halaman terpisah sederhana — Livewire memungkinkan modal tanpa reload, dipilih untuk mengurangi context-switch |

## Responsive & Mobile-First

Mahasiswa mayoritas mengakses dari HP, bukan desktop — semua breakpoint didesain **mobile-first** (base style untuk mobile, `md:`/`lg:` di Tailwind untuk memperluas ke desktop), bukan sebaliknya.

## Aksesibilitas Dasar (bukan kepatuhan penuh WCAG, tapi baseline wajar)

- Kontras warna teks-background minimal cukup terbaca (dicek manual dengan browser devtools, bukan tooling audit formal di MVP)
- Semua form input punya `<label>` yang terasosiasi, bukan hanya placeholder
- Gambar upload (avatar, gallery) punya `alt` text — default ke caption jika ada, ke nama file jika tidak

## Dampak ke Dokumen Lain

Dengan ini final, [`09-design-system.md`](./09-design-system.md) bisa mendefinisikan token visual konkret (warna, tipografi, komponen) yang mengikuti prinsip di atas.
