# Ilyra AttendEase — Sistem Absensi Karyawan Berbasis QR Code & GPS

![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-Compose-2496ED?style=for-the-badge&logo=docker&logoColor=white)
![Status](https://img.shields.io/badge/Status-In%20Development-yellow?style=for-the-badge)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

> Sistem informasi absensi karyawan berbasis web yang dilengkapi dengan validasi **QR Code harian** dan verifikasi **lokasi GPS** secara real-time. Dibangun menggunakan framework Laravel 13, dikontainerisasi menggunakan Docker.

> **Project ini sedang dalam tahap pengembangan aktif.**

**Repository:** [github.com/setiawanandre06/ilyra-attendease](https://github.com/setiawanandre06/ilyra-attendease)

---

## 🚧 Progress Pengembangan

| Fase | Status | Keterangan |
|---|---|---|
| Setup Docker (PHP 8.4, Nginx, MySQL) | Selesai | Laravel 13 berjalan di atas Docker |
| Autentikasi (Laravel Breeze) | Selesai | Login, register, reset password |
| Multi-role (Spatie Permission) | Dalam proses | Setup roles & permissions |
| CRUD Karyawan & Departemen | Belum | - |
| Fitur QR Code Check-in/out | Belum | - |
| Validasi Lokasi GPS | Belum | - |
| Dashboard & Grafik | Belum | - |
| Laporan & Export Excel/PDF | Belum | - |
| Unit & Feature Testing | Belum | - |
| Dokumentasi Lengkap | Belum | - |

---

## ✨ Fitur yang Direncanakan

- **Autentikasi Multi-Role** — Super Admin, HR/Manager, Karyawan
- **QR Code Check-in/out** — QR unik per hari, expired otomatis
- **Validasi Lokasi GPS** — Absensi hanya dalam radius kantor
- **Dashboard Interaktif** — Rekap kehadiran harian & grafik bulanan
- **Export Laporan** — Export ke Excel (.xlsx) dan PDF
- **Security** — CSRF protection, rate limiting, activity log
- **Testing** — Unit & Feature Test dengan PHPUnit

---

## 🛠️ Tech Stack

| Kategori | Teknologi |
|---|---|
| Backend Framework | Laravel 13 |
| Language | PHP 8.4 |
| Database | MySQL 8.0 |
| Frontend | Blade + Tailwind CSS |
| Authentication | Laravel Breeze |
| Authorization | Spatie Laravel Permission |
| QR Code | SimpleSoftwareIO/simple-qrcode *(planned)* |
| Export | Maatwebsite/Laravel-Excel, barryvdh/laravel-dompdf *(planned)* |
| Testing | PHPUnit *(planned)* |
| Containerization | Docker + Docker Compose |
| Web Server | Nginx (Alpine) |

---

## Cara Instalasi

### Prasyarat
- Docker & Docker Compose
- Node.js >= 20.x
- Git

### Langkah Instalasi

```bash
# 1. Clone repository
git clone https://github.com/setiawanandre06/ilyra-attendease.git
cd ilyra-attendease

# 2. Salin file environment
cp .env.example .env

# 3. Sesuaikan konfigurasi database di .env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=ilyra_attendease
DB_USERNAME=root
DB_PASSWORD=your_password

# 4. Build & jalankan Docker
docker compose up -d --build

# 5. Generate application key
docker compose exec app php artisan key:generate

# 6. Jalankan migrasi
docker compose exec app php artisan migrate

# 7. Install & build frontend
npm install
npm run build
```

Akses aplikasi di: `http://localhost:8002`

---

## 🐳 Docker Services

| Service | Container | Port |
|---|---|---|
| PHP-FPM (App) | ilyra_attendease_app | 9000 (internal) |
| Nginx | ilyra_attendease_nginx | 8002 |
| MySQL | ilyra_attendease_mysql | 3307 |

### Perintah Docker Sehari-hari

```bash
# Jalankan semua container
docker compose up -d

# Stop semua container
docker compose down

# Masuk ke container app
docker compose exec app bash

# Artisan command
docker compose exec app php artisan <command>

# Lihat log
docker compose logs -f
```

---

## Author

**Andre Setiawan**
- GitHub: [github.com/setiawanandre06](https://github.com/setiawanandre06)

---

## 📝 Lisensi

Project ini menggunakan lisensi [MIT](LICENSE).

---

<p align="center">Dibuat menggunakan Laravel 13 & Docker</p>