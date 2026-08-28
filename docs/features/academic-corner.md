# Academic Corner

> **Status:** MVP / P0

## Tujuan

Menyediakan informasi akademik dasar kelas tanpa mengubah Digital Class Universe menjadi portal akademik formal.

## Scope MVP

- jadwal,
- dosen,
- deadline,
- pengumuman.

## User Flow

```text
User login
  ↓
Dashboard / Academic Corner
  ↓
Lihat informasi akademik
```

Class Admin/pengurus dapat mengelola informasi yang memang menjadi tanggung jawab kelas.

## Business Rules

- Informasi harus ter-scope ke class.
- Pengumuman resmi hanya dapat dibuat oleh role yang berwenang.
- Data yang sudah tidak relevan boleh diarsipkan daripada dihapus tanpa jejak jika diperlukan.
- Jangan memasukkan integrasi akademik kampus ke MVP.

## Backend

- Gunakan Model + migration yang sederhana.
- Validasi input di server.
- Gunakan Policy untuk aksi admin.
- Pastikan query menggunakan class scope.

## Frontend

- Mobile-first.
- Tampilkan empty state jika belum ada jadwal/deadline.
- Gunakan komponen Blade/Livewire yang dapat dipakai ulang.

## Testing

Prioritaskan:

- user tanpa membership tidak dapat melihat data kelas,
- member hanya dapat membaca,
- Class Admin dapat membuat/mengubah data sesuai permission,
- data antar-class tidak bocor.

## Related Documentation

- [Product](../01-product.md)
- [Development](../02-development.md)
- [Architecture](../03-architecture.md)
- [Database](../04-database.md)
