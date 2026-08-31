# Database

Dokumen ini menjawab: **"Data ini disimpan di mana, dan berhubungan dengan apa?"**

## 1. Database Technology

**MySQL**, diakses lewat **Eloquent** (ORM bawaan Laravel — cara kita baca/tulis database pakai kode PHP, bukan SQL manual).

## 2. Naming Convention

- Nama tabel: huruf kecil, jamak, pakai underscore — contoh `posts`, `gallery_media`, `class_memberships`
- Nama kolom: huruf kecil, underscore — contoh `birth_date`, `created_at`
- Primary key: selalu `id` (auto-increment)
- Foreign key: `{nama_tabel_tunggal}_id` — contoh `user_id`, `class_id`, `poll_id`
- Tiap tabel otomatis punya `created_at` dan `updated_at` (bawaan Eloquent)

## 3. Aturan Global yang Berlaku Semua Tabel

1. **Semua tabel yang menyimpan data milik satu kelas wajib punya kolom `class_id`.** Setiap query otomatis difilter berdasarkan `class_id` lewat *global scope* Eloquent — jadi kita tidak perlu (dan tidak boleh) menambahkan `where('class_id', ...)` manual di tiap controller/component. Ini penting supaya data satu kelas tidak pernah bocor ke kelas lain kalau nanti aplikasi ini dipakai lebih dari satu kelas.
2. **Soft delete** dipakai di tabel yang datanya termasuk kenangan (posts, gallery_media, timeline_entries, comments) — data yang dihapus tidak langsung hilang permanen, ada jeda untuk pemulihan.
3. **Visibility** — kolom `visibility` dipakai berulang di banyak tabel, nilainya salah satu dari: `public`, `class_only`, `admin_only`, `private`. Lihat penjelasan lengkap privacy model di [`01-product.md`](./01-product.md) §8.

## 4. Main Tables

### `users` — akun login

| Kolom | Tipe | Catatan |
|---|---|---|
| id | bigint | primary key |
| name | string | |
| email | string | unique |
| password | string | ter-hash otomatis |

### `classes` — data kelas

| Kolom | Tipe | Catatan |
|---|---|---|
| id | bigint | |
| name | string | contoh: "TI-6A" |
| join_code | string | unique, kode undangan untuk registrasi |
| graduation_target_date | date, nullable | untuk countdown wisuda |

### `class_memberships` — relasi user ↔ kelas, tempat role & status disimpan

**Ini tabel paling penting untuk dipahami.** Role dan status disimpan di sini, **bukan** di tabel `users` — karena satu user bisa punya role berbeda kalau ikut lebih dari satu kelas, dan statusnya bisa berubah (misalnya jadi alumni).

| Kolom | Tipe | Catatan |
|---|---|---|
| id | bigint | |
| user_id | FK → users | |
| class_id | FK → classes | |
| role | enum | `super_admin`, `class_admin`, `moderator`, `member` |
| status | enum | `active`, `alumni`, `inactive` |
| joined_at | timestamp | |

### `profiles` — data profil (satu-satu dengan `users`)

| Kolom | Tipe | Catatan |
|---|---|---|
| id | bigint | |
| user_id | FK → users, unique | |
| nickname | string, nullable | |
| avatar_path | string, nullable | |
| bio | text, nullable | |
| hobby | json, nullable | array string |
| favorite | json, nullable | array string |
| fun_fact | text, nullable | |
| birth_date | date, nullable | |
| birth_year_visible | boolean | default `false` — tahun lahir tidak pernah dipaksa tampil |
| birthday_visibility | enum | default `class_only` |
| profile_visibility | enum | default `class_only` |

### `events` — event countdown

| Kolom | Tipe | Catatan |
|---|---|---|
| id, class_id | | |
| title, description | | |
| starts_at | datetime | disimpan UTC, dikonversi timezone saat ditampilkan |
| location | string, nullable | |
| image_path | string, nullable | |
| organizer_id | FK → users, nullable | |
| status | enum | `upcoming`, `ongoing`, `completed`, `cancelled` |
| visibility | enum | |

### `posts` — social feed

| Kolom | Tipe | Catatan |
|---|---|---|
| id, class_id | | |
| author_id | FK → users | |
| type | enum | `text`, `image`, `announcement`, `memory` |
| body | text, nullable | |
| media_path | string, nullable | |
| visibility | enum | |
| deleted_at | | soft delete |

### `comments` — dipakai bersama untuk posts, timeline_entries, gallery_media

Tabel ini **polymorphic** — satu tabel dipakai untuk komentar di banyak jenis konten (post, timeline, gallery), supaya kita tidak perlu bikin `post_comments`, `gallery_comments`, dst. terpisah.

| Kolom | Tipe | Catatan |
|---|---|---|
| id | | |
| commentable_id, commentable_type | | menunjuk ke konten yang dikomentari |
| author_id | FK → users | |
| body | text | |
| deleted_at | | soft delete |

### `reactions` — dipakai bersama untuk posts, comments, timeline_entries, gallery_media

Sama seperti `comments`, ini juga polymorphic.

| Kolom | Tipe | Catatan |
|---|---|---|
| id | | |
| reactable_id, reactable_type | | menunjuk ke konten yang di-react |
| user_id | FK → users | |
| type | enum | `like` (baru satu jenis di MVP) |

Kombinasi (`reactable_id`, `reactable_type`, `user_id`, `type`) dibuat **unique** — supaya satu user tidak bisa react dua kali ke konten yang sama.

### `polls`, `poll_options`, `poll_votes` — polling

| Tabel | Kolom penting |
|---|---|
| `polls` | id, class_id, creator_id, question, is_anonymous, result_visibility, closes_at, status |
| `poll_options` | id, poll_id, label |
| `poll_votes` | id, poll_option_id, user_id — **unique per poll per user**, mencegah vote dobel |

### `gallery_albums`, `gallery_media`, `media_tags` — class gallery

| Tabel | Kolom penting |
|---|---|
| `gallery_albums` | id, class_id, title, category (`class_photo`/`event`/`campus`/`random`/`meme`/`throwback`) |
| `gallery_media` | id, album_id, uploader_id, file_path, caption, visibility, deleted_at |
| `media_tags` | id, gallery_media_id, tagged_user_id |

### `timeline_entries`, `timeline_media` — class timeline

| Tabel | Kolom penting |
|---|---|
| `timeline_entries` | id, class_id, author_id, title, story, event_date, deleted_at |
| `timeline_media` | id, timeline_entry_id, file_path |

Komentar & reaksi di timeline pakai tabel `comments`/`reactions` yang sama di atas, bukan tabel baru.

### `achievements`, `user_achievements` — achievement system

| Tabel | Kolom penting |
|---|---|
| `achievements` | id, class_id, code (unique, misal `first_login`), name, description, icon, trigger_type |
| `user_achievements` | id, user_id, achievement_id, awarded_at — **unique per user per achievement** |

### `reports` — moderasi, polymorphic (target: posts, comments, gallery_media, timeline_entries, profiles)

| Kolom | Tipe | Catatan |
|---|---|---|
| id | | |
| reportable_id, reportable_type | | konten yang dilaporkan |
| reporter_id | FK → users | |
| reason | text | |
| status | enum | `pending`, `reviewed`, `dismissed` |
| reviewed_by | FK → users, nullable | |

### `audit_logs` — jejak aksi moderasi/admin

| Kolom | Tipe | Catatan |
|---|---|---|
| id | | |
| actor_id | FK → users | siapa melakukan aksi |
| action | string | contoh: `report.reviewed`, `poll.closed` |
| target_id, target_type | | |
| meta | json, nullable | |

## 5. Relationships (Ringkas)

```
User 1—1 Profile
User 1—N ClassMembership N—1 Class
User 1—N Post, Event(as organizer), GalleryMedia(as uploader), TimelineEntry(as author)
Post, TimelineEntry, GalleryMedia 1—N Comment, Reaction (polymorphic)
Poll 1—N PollOption 1—N PollVote
GalleryAlbum 1—N GalleryMedia 1—N MediaTag
Achievement 1—N UserAchievement N—1 User
```

## 6. Migration

Setiap perubahan struktur tabel **wajib** lewat file migration baru — jangan pernah mengubah tabel manual lewat phpMyAdmin/GUI database di server, karena perubahan itu tidak akan tercatat dan tidak akan otomatis ada di komputer anggota tim lain.

```bash
php artisan make:migration create_events_table
```

Migration baru yang menambah kolom di tabel yang sudah berisi data **harus** aman untuk data lama — kolom baru dibuat `nullable` atau punya default value, bukan langsung `NOT NULL` tanpa default.

## 7. Seeder

Seeder dipakai untuk mengisi database dengan data contoh saat development (lihat [`02-development.md`](./02-development.md) §5) — bukan untuk data production. Seeder MVP menyediakan: 1 kelas contoh, beberapa member dummy dengan role berbeda, beberapa post/event contoh.

```bash
php artisan db:seed
```

## 8. Example Data

Contoh isi `class_memberships` untuk memperjelas bagaimana role & status dipakai:

| user_id | class_id | role | status |
|---|---|---|---|
| 1 | 1 | class_admin | active |
| 2 | 1 | moderator | active |
| 3 | 1 | member | active |
| 4 | 1 | member | alumni |

## 9. Rules

- Query **tidak boleh** melewati global scope `class_id` — jangan pakai `DB::table()` mentah tanpa filter kelas kecuali benar-benar diperlukan dan sudah didiskusikan.
- Field sensitif (birthday, kontak) **selalu** dicek `visibility`-nya sebelum ditampilkan ke user lain.
- Jangan hapus data secara permanen (`forceDelete`) untuk tabel yang pakai soft delete, kecuali memang diminta eksplisit — prinsip *Memory Forever* berarti data kenangan tidak boleh gampang hilang.
- Unique constraint (vote, reaction, achievement) tidak boleh dilewati dengan validasi di level aplikasi saja — harus benar-benar jadi constraint di database.

## 10. Cara Menambah/Mengubah Struktur Database

1. Diskusikan dulu di issue kalau perubahan memengaruhi tabel yang sudah dipakai fitur lain
2. Buat migration baru (`php artisan make:migration ...`)
3. Update Model terkait (`$fillable`, relationship)
4. Kalau perlu, update seeder supaya data contoh tetap konsisten
5. Jalankan `php artisan migrate` di lokal, pastikan tidak error
6. Commit migration + perubahan Model dalam PR yang sama, jangan terpisah
