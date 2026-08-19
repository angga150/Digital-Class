# 06 — Authorization

Status: ✅ Final untuk MVP. Terjemahan teknis dari [`product/04-roles-and-permissions.md`](../product/04-roles-and-permissions.md) dan [`product/05-privacy-and-moderation.md`](../product/05-privacy-and-moderation.md).

## Mekanisme: Laravel Policy + Gate, Berbasis `class_memberships.role`

Tidak memakai package role/permission pihak ketiga (mis. Spatie Permission) di MVP — jumlah role tetap (5) dan aturan tidak berubah-ubah dinamis, sehingga Policy/Gate bawaan Laravel sudah cukup dan lebih mudah dipahami tim mahasiswa tanpa dependency tambahan (prinsip *Simple First*). Dipertimbangkan ulang jika kebutuhan role custom per-kelas muncul di fase multi-tenant lanjutan.

### Cara Kerja

1. Role user-di-kelas-tertentu diambil dari `class_memberships.role` (bukan dari `users` langsung) — lihat [`03-database.md`](./03-database.md).
2. Setiap domain module (Feed, Poll, Gallery, dst.) punya satu Policy class, mis. `PostPolicy`, `PollPolicy`.
3. Policy method menerima `User $user` dan `Class $class` (via record terkait), mengecek role lewat helper `$user->roleIn($class)`.

```php
// Contoh: App\Policies\PostPolicy
public function moderate(User $user, Post $post): bool
{
    $role = $user->roleIn($post->class);
    return in_array($role, ['super_admin', 'class_admin', 'moderator']);
}
```

## Permission Matrix → Implementasi

Tabel di [`product/04-roles-and-permissions.md`](../product/04-roles-and-permissions.md) diterjemahkan 1:1 menjadi Policy method. Ringkasan pemetaan:

| Action (produk) | Policy method | Module |
|---|---|---|
| Kelola pengaturan kelas | `ClassPolicy@update` | Membership |
| Kelola user & role | `ClassMembershipPolicy@manage` | Membership |
| Buat post di feed | `PostPolicy@create` | Feed |
| Buat pengumuman/event resmi | `EventPolicy@create` | Event |
| Buat polling | `PollPolicy@create` | Poll |
| Vote polling | `PollPolicy@vote` | Poll |
| Moderasi konten (report/hide) | `{Model}Policy@moderate` per module | Moderation |
| Kelola birthday sendiri | `ProfilePolicy@update` (cek `$profile->user_id === $user->id`) | Profile |
| Upload ke gallery/timeline | `GalleryMediaPolicy@create`, `TimelineEntryPolicy@create` | Gallery/Timeline |
| Lihat audit log | `AuditLogPolicy@viewAny` | Core |

## Status pada Membership Memengaruhi Permission

Selain `role`, `class_memberships.status` (`active`/`alumni`/`inactive`) juga dicek di Policy — bukan hanya role. Contoh: status `alumni` mendapat izin baca penuh tapi tidak bisa membuat post/upload baru (lihat catatan future user Alumni di [`product/03-target-user-analysis.md`](../product/03-target-user-analysis.md)). Ini belum aktif dipakai di MVP satu kelas (semua member berstatus `active`), tapi struktur Policy-nya sudah mengakomodasi sejak awal agar tidak perlu refactor saat status alumni mulai relevan.

```php
public function create(User $user, Class $class): bool
{
    $membership = $user->membershipIn($class);
    return $membership && $membership->status === 'active';
}
```

## Privacy Enforcement (Field-Level) — Terpisah dari Role-Based Policy

Role/Policy menjawab "boleh melakukan aksi apa", sedangkan **visibility** (`public/class_only/admin_only/private`) menjawab "boleh melihat data apa" — dua mekanisme berbeda, keduanya wajib jalan bersamaan:

- Policy (`view`) mengecek role & status
- **Visibility check** (helper `Visible::to($user, $record)`) mengecek field `visibility` pada record itu sendiri

Contoh: seorang Member (role sah, boleh lihat profile orang lain) tetap tidak boleh melihat `birth_date` yang di-set `private` oleh pemiliknya — ini dicek di level presenter/resource, bukan di Policy aksi.

## Guest (Non-Login)

Tidak ada baris `class_memberships` untuk Guest. Middleware `auth` dan `verified` menutup hampir semua route MVP — Guest di MVP hanya bisa mengakses halaman login/registrasi via join code. Akses publik terbatas (mis. landing page kelas) dievaluasi terpisah jika dibutuhkan, tidak didesain di MVP.

## Dampak ke Dokumen Lain

Dengan ini final, [`04-api.md`](./04-api.md) dan [`07-features/`](./07-features/) bisa mengacu ke Policy method di atas saat mendefinisikan endpoint & aturan per fitur.
