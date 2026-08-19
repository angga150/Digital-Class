# 09 — Design System

Status: ✅ Final untuk MVP (versi minimal, cukup untuk konsistensi tim, bukan design system lengkap ala perusahaan besar).

## Tooling: Tailwind CSS + Komponen Blade

Tidak membangun component library terpisah (Storybook, dsb.) — overkill untuk tim mahasiswa. Konsistensi dicapai lewat **Blade component** (`<x-card>`, `<x-button>`) yang membungkus kelas Tailwind, sehingga style tidak diketik ulang berbeda-beda di tiap halaman oleh anggota tim berbeda.

## Design Token (Ringkas)

| Token | Nilai | Catatan |
|---|---|---|
| Warna primer | 1 warna aksen hangat (mis. oranye/kuning keemasan) | Selaras *Fun First* — bukan biru korporat default |
| Warna netral | Skala abu Tailwind default (`slate` atau `zinc`) | Tidak perlu kustomisasi, hemat waktu |
| Font | Font system default Tailwind (`font-sans`) di MVP | Custom font (Google Fonts) opsional P1 — bukan prioritas |
| Radius | Rounded konsisten (`rounded-lg` sebagai default card/button) | Memberi kesan ramah/kasual, bukan tajam/formal |
| Spacing | Skala default Tailwind, tidak custom | Konsistensi datang dari disiplin pakai skala default, bukan menambah token baru |

## Komponen Wajib (Blade Component)

| Komponen | Dipakai di |
|---|---|
| `<x-card>` | Dashboard widget, gallery item, achievement badge |
| `<x-button>` (variant: primary/secondary/danger) | Semua aksi (submit, delete, dsb.) |
| `<x-avatar>` | Profile, feed, comment, member list — termasuk fallback inisial (lihat `07-features/members.md`) |
| `<x-badge>` | Achievement, role label |
| `<x-visibility-toggle>` | Dipakai di form profil/post/event — implementasi visual dari privacy model, dipakai berulang agar toggle visibility terasa konsisten di semua fitur |
| `<x-empty-state>` | Dipakai saat data kosong (feed baru, gallery kosong, dsb.) — konsisten dengan catatan edge case di `07-features/dashboard.md` |
| `<x-modal>` | Form post/event/poll baru (Livewire modal, lihat `08-ui-ux.md`) |

## Ikon & Ilustrasi

- Ikon: [Heroicons](https://heroicons.com/) (dibuat oleh tim Tailwind, terintegrasi mudah, gratis) — tidak perlu icon library berbayar.
- Ilustrasi/emoji: emoji native dipakai untuk elemen playful (birthday 🎂, achievement 🏆) — lebih murah daripada custom illustration dan cukup untuk MVP.

## Dark Mode

**Tidak masuk MVP.** Dievaluasi di P2 jika ada permintaan nyata dari pengguna — menambah dark mode sejak awal berarti dua kali kerja styling untuk manfaat yang belum tentu dipakai.
