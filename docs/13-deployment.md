# 13 — Deployment Strategy

Status: ✅ Final untuk MVP.

## Hosting: VPS Murah atau Platform-as-a-Service Sederhana

Tidak memakai infrastruktur cloud kompleks (AWS/GCP multi-service) — di luar kebutuhan dan skill tim mahasiswa di MVP. Dua opsi yang sepadan, pilih berdasarkan kenyamanan tim:

| Opsi | Kapan cocok |
|---|---|
| VPS murah (mis. kontainer Laravel Forge/Ploi terkelola, atau VPS manual) | Tim punya waktu belajar sedikit server admin, ingin kontrol penuh |
| PaaS (mis. Railway, Render) | Tim ingin deploy secepat mungkin tanpa urus server, terima trade-off sedikit lebih mahal/terbatas di free tier |

Bukan keputusan yang mengunci arsitektur — karena modular monolith Laravel biasa (§`02-architecture.md`), migrasi antar hosting relatif mudah kapan pun dibutuhkan.

## Environment

| Environment | Fungsi |
|---|---|
| Local | Development sehari-hari tiap anggota tim (Laravel Sail/Herd/XAMPP — bebas dipilih individu) |
| Staging *(opsional, aktifkan jika tim punya kapasitas)* | Tempat uji sebelum ke production, memakai data dummy — **boleh dilewati di MVP awal** jika tim kecil, langsung dari local ke production dengan hati-hati |
| Production | Yang dipakai kelas sungguhan |

## Proses Deploy (Manual di MVP, Bukan Auto-Deploy)

Auto-deploy (CI/CD penuh dari merge ke `master` langsung live) **ditunda** — MVP memakai deploy manual terpicu (`git pull` + `php artisan migrate` + `php artisan optimize` di server) setelah PR di-merge dan dicek dulu. Alasan: tim kecil, frekuensi deploy tidak tinggi, dan auto-deploy tanpa staging environment matang berisiko fitur belum matang langsung live ke kelas sungguhan.

Auto-deploy dipertimbangkan lagi setelah `12-testing.md` (CI) berjalan stabil dan tim punya staging environment.

## Environment Variables

`.env` production **tidak pernah** di-commit ke repo (lihat `.gitignore` di root) — dikelola manual per hosting (dashboard PaaS atau file `.env` langsung di VPS via SSH).

## Database Migration di Production

`php artisan migrate` dijalankan manual setelah deploy kode baru — **bukan** `migrate:fresh` (yang menghapus semua data). Migration baru harus backward-compatible dengan data existing (mis. kolom baru nullable atau punya default, bukan langsung `NOT NULL` tanpa default pada tabel yang sudah berisi data).

## Domain & SSL

Domain kustom (jika tersedia) atau subdomain gratis dari platform hosting. HTTPS wajib (Let's Encrypt otomatis di kebanyakan PaaS/panel VPS modern) — bukan opsional, karena ada data login & data pribadi (birthday, dsb.) yang lewat form.

## Monitoring (Minimal)

Tidak memakai APM berbayar (New Relic, dsb.) di MVP — cukup Laravel log file + uptime check gratis (mis. UptimeRobot) untuk tahu kalau situs down. Ditingkatkan jika skala produk berkembang.
