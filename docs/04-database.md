# Database

## 1. Database Technology

**MySQL** digunakan untuk development utama dan production MVP.

Setiap perubahan schema dilakukan melalui Laravel migration.

## 2. Aturan Data Utama

- `users` menyimpan identitas login.
- `class_memberships` menghubungkan user dengan class.
- Role dan status berada di membership.
- Data feature yang milik kelas di-scope dengan `class_id`.
- Profile dipisahkan dari user.
- Data yang perlu soft delete menggunakan `deleted_at`.
- Constraint database digunakan untuk mencegah duplikasi penting.

## 3. Naming Convention

Gunakan convention Laravel:

- tabel plural snake_case,
- primary key `id`,
- foreign key `<model>_id`,
- timestamp `created_at`, `updated_at`,
- soft delete `deleted_at`.

## 4. Tabel Fondasi

### `users`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | identity |
| name | string | nama |
| email | string unique | login |
| password | string | hashed |
| email_verified_at | timestamp nullable | verifikasi |

### `classes`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| name | string | contoh `TI-6A` |
| graduation_target_date | date nullable | countdown |
| join_code | string unique | kode join |
| join_code_enabled | boolean | dapat dimatikan |

Hierarki Faculty/Department/Campus tidak dibuat sebagai tabel di MVP.

### `class_memberships`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigint PK | |
| user_id | FK | user |
| class_id | FK | class |
| role | enum | `super_admin`, `class_admin`, `moderator`, `member` |
| status | enum | `active`, `alumni`, `inactive` |
| joined_at | timestamp | |

Unique:

```text
(user_id, class_id)
```

### `profiles`

Relasi 1:1 dengan `users`.

| Kolom | Tipe |
|---|---|
| id | bigint PK |
| user_id | FK unique |
| nickname | string nullable |
| avatar_path | string nullable |
| bio | text nullable |
| hobby | json nullable |
| favorite | json nullable |
| fun_fact | text nullable |
| birth_date | date nullable |
| birth_year_visible | boolean default false |
| birthday_visibility | Visibility |
| profile_visibility | Visibility |

Profile sengaja tidak digabung ke `users` agar tabel authentication tetap ramping.

## 5. Tabel Feature

### `events`

`id`, `class_id`, `title`, `description`, `starts_at`, `location`, `image_path`, `organizer_id`, `status`, `visibility`.

Status:

```text
upcoming
ongoing
completed
cancelled
```

`starts_at` disimpan UTC dan dikonversi saat ditampilkan.

### `posts`

`id`, `class_id`, `author_id`, `type`, `body`, `media_path`, `visibility`, `deleted_at`.

Type:

```text
text
image
announcement
memory
```

Event/poll/achievement dapat direferensikan di feed tanpa menduplikasi data.

### `comments`

Polymorphic:

```text
commentable_id
commentable_type
author_id
body
deleted_at
```

Dipakai untuk posts, timeline entries, dan gallery media.

### `reactions`

Polymorphic:

```text
reactable_id
reactable_type
user_id
type
```

Unique:

```text
(reactable_id, reactable_type, user_id, type)
```

MVP menggunakan satu jenis reaction, misalnya `like`.

### `polls`

`id`, `class_id`, `creator_id`, `question`, `is_anonymous`, `result_visibility`, `closes_at`, `status`.

### `poll_options`

`id`, `poll_id`, `label`.

### `poll_votes`

`id`, `poll_id`, `poll_option_id`, `user_id`.

`poll_id` sengaja didenormalisasi agar unique constraint untuk satu user per poll dapat diterapkan dengan jelas.

### `gallery_albums`

`id`, `class_id`, `title`, `category`.

Kategori:

```text
class_photo
event
campus
random
meme
throwback
```

### `gallery_media`

`id`, `album_id`, `uploader_id`, `file_path`, `caption`, `visibility`, `deleted_at`.

### `media_tags`

`id`, `gallery_media_id`, `tagged_user_id`.

### `timeline_entries`

`id`, `class_id`, `author_id`, `title`, `story`, `event_date`, `deleted_at`.

### `timeline_media`

`id`, `timeline_entry_id`, `file_path`.

### `achievements`

`id`, `class_id`, `code`, `name`, `description`, `icon`, `trigger_type`.

Trigger:

```text
event_based
manual
```

MVP 3–5 badge dasar:

- `first_login`
- `profile_complete`
- `historian`

### `user_achievements`

`id`, `user_id`, `achievement_id`, `awarded_at`.

Gunakan unique constraint `(user_id, achievement_id)`.

## 6. Moderation dan Audit

### `reports`

Polymorphic target:

```text
posts
comments
gallery_media
timeline_entries
profiles
```

Kolom utama:

```text
id
reportable_id
reportable_type
reporter_id
reason
status
reviewed_by
```

Status:

```text
pending
reviewed
dismissed
```

### `audit_logs`

```text
id
actor_id
action
target_id
target_type
meta
```

Contoh action:

```text
report.reviewed
poll.closed
member.role_changed
```

Audit hanya untuk aksi penting, terutama moderasi dan perubahan role; jangan mencatat setiap request.

## 7. Relationship Inti

```text
User
 ├── Profile
 └── ClassMembership
       └── Class

Class
 ├── Events
 ├── Posts
 ├── Polls
 ├── Gallery Albums
 ├── Timeline Entries
 └── Achievements
```

`comments`, `reactions`, dan `reports` menggunakan polymorphic relationship.

## 8. Migration

Perubahan schema harus dibuat sebagai migration:

```bash
php artisan make:migration add_xxx_to_xxx_table
php artisan migrate
```

Development lokal dapat di-reset:

```bash
php artisan migrate:fresh --seed
```

Jangan gunakan `migrate:fresh` di production.

Migration production harus aman terhadap data yang sudah ada. Hindari menambahkan kolom `NOT NULL` tanpa default/strategi migrasi pada tabel berisi data.

## 9. Seeder

Seeder menyediakan data dummy minimal:

- satu class contoh,
- beberapa member,
- beberapa profile,
- beberapa post/event/poll jika implementasi sudah tersedia.

Tujuannya agar developer dapat langsung mencoba feature.

## 10. Data P1+ Tidak Dibuat Sekarang

Tidak ada tabel MVP untuk:

- Confession
- Class Finance
- Time Capsule

Schema dibuat saat feature benar-benar dikerjakan. Jangan membuat tabel kosong hanya untuk "persiapan masa depan".

## 11. Rule yang Wajib Dijaga

### `class_id` scoping

Setiap query data kelas harus memastikan record berasal dari kelas yang sedang diakses user.

### Unique constraint

Gunakan database constraint untuk aturan seperti:

- membership user-class,
- reaction user-target,
- vote user-poll,
- achievement user.

### Soft delete

Post, gallery media, timeline, dan konten yang memang memerlukan pemulihan dapat menggunakan soft delete.

### Privacy

Data sensitif jangan diberikan dari query/presenter jika viewer tidak berhak.

## 12. Storage

Database menyimpan **path file**, bukan binary file.

Detail storage ada di [`05-deployment.md`](05-deployment.md).
