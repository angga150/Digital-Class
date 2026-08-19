# 04 — API Design

Status: ✅ Final untuk MVP.

## Konteks Penting: API Ini Bukan Konsumen Utama di MVP

Frontend MVP adalah Blade + Livewire (lihat [`02-architecture.md`](./02-architecture.md)), yang berinteraksi langsung dengan Controller/Livewire component — **bukan** lewat lapisan REST API. Dokumen ini tetap dibuat karena dua alasan:

1. Service layer di setiap domain module didesain agar bisa diekspos sebagai API tanpa refactor besar (untuk kebutuhan future: mobile app, integrasi kampus).
2. Beberapa interaksi ringan di Livewire (mis. polling AJAX kecil untuk notifikasi) tetap memakai pola request/response yang konsisten dengan konvensi di bawah, walau tidak selalu lewat route `/api/*`.

Endpoint di bawah ini **disiapkan strukturnya**, tapi implementasinya boleh menyusul bertahap seiring fitur dikerjakan (tidak semua perlu dibangun sebelum Livewire versinya jalan).

## Konvensi

- **Base path:** `/api/v1/`
- **Versioning:** prefix versi di URL (`v1`) sejak awal — perubahan breaking di masa depan tidak memaksa migrasi client existing.
- **Format:** JSON, memakai [Laravel API Resource](https://laravel.com/docs/eloquent-resources) untuk transformasi konsisten (bukan `return $model` mentah).
- **Auth:** Laravel Sanctum (token-based), **ditambahkan sebagai layer terpisah** dari session auth web — lihat [`05-authentication.md`](./05-authentication.md) §"Kenapa Bukan Token-Based Auth di MVP". Middleware `auth:sanctum` khusus route `/api/*`, tidak mengganggu `auth` (session) di route web.
- **Scoping:** semua endpoint otomatis ter-scope `class_id` dari membership user yang sedang login (dari token) — bukan parameter yang dikirim client, untuk mencegah user mengakses data kelas lain dengan mengubah parameter.
- **Error format:**
```json
{
  "message": "Deskripsi error singkat",
  "errors": { "field": ["detail validasi"] }
}
```
- **Pagination:** cursor/page-based bawaan Laravel (`?page=`), response menyertakan `meta.current_page`, `meta.last_page`.

## Endpoint per Module (Ringkas)

Semua endpoint tunduk pada Policy yang sama dengan versi web — lihat pemetaan lengkap di [`06-authorization.md`](./06-authorization.md). Tabel di bawah hanya mendaftar route, bukan mengulang aturan izin.

### Membership & Profile
| Method | Endpoint | Deskripsi |
|---|---|---|
| GET | `/api/v1/me` | Profil user login + membership aktif |
| PATCH | `/api/v1/profile` | Update profil sendiri |
| GET | `/api/v1/members` | Daftar member kelas (field mengikuti visibility) |
| GET | `/api/v1/members/{id}` | Detail satu member |

### Dashboard
| Method | Endpoint | Deskripsi |
|---|---|---|
| GET | `/api/v1/dashboard` | Payload gabungan: birthday terdekat, event terdekat, polling aktif, achievement terbaru — endpoint agregat, bukan gabungan manual di client |

### Birthday & Event
| Method | Endpoint | Deskripsi |
|---|---|---|
| GET | `/api/v1/birthdays/upcoming` | Birthday terdekat sesuai visibility |
| GET | `/api/v1/events` | Daftar event (filter `status`) |
| POST | `/api/v1/events` | Buat event (Class Admin) |
| PATCH/DELETE | `/api/v1/events/{id}` | Update/hapus event |

### Social Feed
| Method | Endpoint | Deskripsi |
|---|---|---|
| GET | `/api/v1/posts` | Feed, paginated |
| POST | `/api/v1/posts` | Buat post |
| DELETE | `/api/v1/posts/{id}` | Hapus post sendiri (soft delete) |
| POST | `/api/v1/posts/{id}/comments` | Tambah komentar |
| POST | `/api/v1/posts/{id}/reactions` | Toggle reaksi |
| POST | `/api/v1/reports` | Laporkan konten (body: `reportable_type`, `reportable_id`, `reason`) |

### Polling
| Method | Endpoint | Deskripsi |
|---|---|---|
| GET | `/api/v1/polls` | Daftar polling (filter `status`) |
| POST | `/api/v1/polls` | Buat polling (Class Admin) |
| POST | `/api/v1/polls/{id}/vote` | Vote (body: `option_id`) — server menolak vote ganda |
| GET | `/api/v1/polls/{id}/results` | Hasil (tunduk `result_visibility`) |

### Gallery & Timeline
| Method | Endpoint | Deskripsi |
|---|---|---|
| GET | `/api/v1/albums` | Daftar album |
| POST | `/api/v1/albums/{id}/media` | Upload media ke album |
| GET | `/api/v1/timeline` | Entri timeline, urut tanggal |
| POST | `/api/v1/timeline` | Tambah entri timeline |

### Achievement
| Method | Endpoint | Deskripsi |
|---|---|---|
| GET | `/api/v1/achievements` | Daftar achievement kelas + status milik user login |

## Yang Sengaja Tidak Ada di MVP

Endpoint untuk Confession, Class Finance, Time Capsule — mengikuti keputusan yang sama dengan [`03-database.md`](./03-database.md): fitur ini belum punya tabel, jadi belum punya endpoint. Ditambahkan bersamaan saat fitur itu dikerjakan di P1.

## Dampak ke Dokumen Lain

Dengan struktur ini, [`07-features/`](./07-features/) bisa mulai diisi per fitur — merujuk endpoint di atas untuk bagian "Endpoint API terkait" di tiap file.
