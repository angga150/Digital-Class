# Architecture

## 1. Keputusan Teknologi Final

- Laravel
- Livewire
- Blade
- Tailwind CSS
- Alpine.js jika diperlukan
- MySQL

Architecture:

> **Monolithic Laravel Application dengan Livewire**

Tidak menggunakan React, Vue, Inertia, SPA, microservices, Kubernetes, API gateway, atau event-driven infrastructure kompleks untuk MVP.

## 2. High-Level Architecture

```text
Browser
   ↓
Laravel Routes
   ↓
Livewire Components
   ↓
Application / Business Logic
   ↓
Eloquent ORM
   ↓
MySQL
```

Blade dan Tailwind berada di sisi presentation.

## 3. Tanggung Jawab

### Laravel

Menangani:

- routing,
- authentication,
- authorization,
- business logic,
- database interaction,
- validation,
- server-side rendering.

### Livewire

Menangani UI interaktif tanpa frontend SPA terpisah.

### Blade

Menampilkan HTML dan state dari server/Livewire.

### Tailwind

Menangani styling.

### Eloquent

Menjadi cara utama berinteraksi dengan database.

## 4. Struktur Source Code

Semua source code aplikasi berada di:

```text
src/
```

Struktur Laravel di dalamnya tetap normal:

```text
src/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/
├── resources/
├── routes/
├── storage/
├── tests/
├── artisan
├── composer.json
└── package.json
```

Jangan memisahkan menjadi `backend/` dan `frontend/`.

## 5. Domain Boundaries yang Sederhana

Kode boleh dikelompokkan secara logis berdasarkan domain:

```text
Membership & Auth
Dashboard / Birthday / Event
Feed / Poll
Gallery / Timeline
Achievement / Notification
Moderation / Core
```

Tidak perlu membuat struktur module enterprise jika belum dibutuhkan.

## 6. Authentication

MVP menggunakan session-based authentication Laravel, dengan starter kit Laravel Breeze bila digunakan oleh implementasi.

Registrasi tidak terbuka bebas.

Alur:

```text
Class Admin membuat join code
        ↓
Mahasiswa daftar memakai join code
        ↓
users + class_memberships
        ↓
Email verification
        ↓
Akses fitur kelas
```

Join code dapat dinonaktifkan oleh Class Admin.

Social login ditunda. Token authentication untuk web juga tidak diperlukan.

## 7. Authorization

Gunakan Laravel Policy/Gate.

Role berasal dari:

```text
class_memberships.role
```

Bukan dari `users.role`.

Policy menjawab:

> "Boleh melakukan aksi ini?"

Visibility menjawab:

> "Boleh melihat data ini?"

Keduanya tidak boleh dicampur.

Contoh konsep:

```php
public function moderate(User $user, Post $post): bool
{
    $role = $user->roleIn($post->class);

    return in_array($role, [
        'super_admin',
        'class_admin',
        'moderator',
    ]);
}
```

Status membership juga diperiksa. Misalnya `alumni` dapat read-only pada area tertentu dan tidak otomatis dianggap `active`.

## 8. Visibility

Gunakan konsep `Visible::to($user, $record)` atau helper/presenter yang setara.

Visibility:

```text
public
class-only
admin-only
private
```

Jangan mengandalkan UI untuk menyembunyikan data private. Query/presenter/server-side logic harus memastikan data memang tidak diberikan kepada user yang tidak berhak.

## 9. Data Scoping

MVP hanya satu kelas, tetapi setiap data feature tetap membawa `class_id` jika memang merupakan data milik kelas.

Contoh:

```text
posts.class_id
events.class_id
polls.class_id
gallery_albums.class_id
timeline_entries.class_id
```

Jangan menerima `class_id` dari client sebagai satu-satunya dasar authorization. Tentukan kelas dari membership user dan validasi resource terhadap kelas tersebut.

## 10. API: Future Boundary, Bukan Jalur Utama MVP

Web MVP tidak berjalan melalui REST API.

Namun service/business logic sebaiknya tidak dibuat sedemikian rupa sehingga kelak mustahil dipakai oleh API.

Jika API dibutuhkan di masa depan, konvensi yang telah dirancang:

```text
/api/v1/
```

- JSON
- Laravel API Resource
- Sanctum untuk token API
- policy yang sama dengan web
- scoping berdasarkan membership
- pagination Laravel
- format error konsisten

Endpoint rancangan awal:

```text
GET    /api/v1/me
PATCH  /api/v1/profile
GET    /api/v1/members
GET    /api/v1/members/{id}

GET    /api/v1/dashboard

GET    /api/v1/birthdays/upcoming
GET    /api/v1/events
POST   /api/v1/events
PATCH  /api/v1/events/{id}
DELETE  /api/v1/events/{id}

GET    /api/v1/posts
POST   /api/v1/posts
DELETE /api/v1/posts/{id}
POST   /api/v1/posts/{id}/comments
POST   /api/v1/posts/{id}/reactions
POST   /api/v1/reports

GET    /api/v1/polls
POST   /api/v1/polls
POST   /api/v1/polls/{id}/vote
GET    /api/v1/polls/{id}/results

GET    /api/v1/albums
POST   /api/v1/albums/{id}/media
GET    /api/v1/timeline
POST   /api/v1/timeline

GET    /api/v1/achievements
```

API bukan alasan untuk membangun REST layer lengkap sebelum kebutuhan nyata ada.

## 11. UI/UX Principles

- Mobile-first
- Responsive
- Consistent spacing
- Reusable components
- Clear loading state
- Clear empty state
- Clear error state
- Accessible interaction

Website akan banyak digunakan melalui smartphone, sehingga mobile-first adalah prinsip utama.

## 12. Scalability yang Sehat

Scalability bukan berarti complexity.

Yang penting:

- responsibility jelas,
- query memiliki scope yang benar,
- index database dibuat ketika diperlukan,
- component reusable,
- authorization benar,
- boundary feature jelas,
- dependency tidak berlebihan.

Gunakan prinsip:

> **Simple architecture that can grow.**
