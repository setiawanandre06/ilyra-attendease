# 📋 Ilyra AttendEase — Sistem Absensi Karyawan Berbasis QR Code & GPS

![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-Compose-2496ED?style=for-the-badge&logo=docker&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

> Sistem informasi absensi karyawan berbasis web yang dilengkapi dengan validasi **QR Code harian** dan verifikasi **lokasi GPS** secara real-time. Dibangun menggunakan framework Laravel 13 dengan pendekatan keamanan dan performa tinggi, dikontainerisasi menggunakan Docker.

🔗 **Repository:** [github.com/setiawanandre06/ilyra-attendease](https://github.com/setiawanandre06/ilyra-attendease)

---

## 📸 Screenshot

| Dashboard Admin | Halaman Absensi | Laporan |
|---|---|---|
| ![dashboard](docs/screenshots/dashboard.png) | ![absensi](docs/screenshots/absensi.png) | ![laporan](docs/screenshots/laporan.png) |

---

## ✨ Fitur Utama

- 🔐 **Autentikasi Multi-Role** — Super Admin, HR/Manager, Karyawan (via Spatie Permission)
- 📱 **QR Code Check-in/out** — QR unik per hari, expired otomatis pukul 12 siang
- 📍 **Validasi Lokasi GPS** — Absensi hanya bisa dilakukan dalam radius yang ditentukan
- 📊 **Dashboard Interaktif** — Rekap kehadiran harian & grafik bulanan
- 📄 **Export Laporan** — Export ke Excel (.xlsx) dan PDF
- 🛡️ **Security** — CSRF protection, rate limiting, activity log, input sanitasi
- 🧪 **Unit & Feature Testing** — Ditulis dengan PHPUnit
- 🐳 **Docker Ready** — Siap jalan dengan Docker Compose

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
| QR Code | SimpleSoftwareIO/simple-qrcode |
| Export | Maatwebsite/Laravel-Excel, barryvdh/laravel-dompdf |
| Testing | PHPUnit (bawaan Laravel) |
| Containerization | Docker + Docker Compose |
| Web Server | Nginx (Alpine) |

---

## 📐 Arsitektur Sistem

### Alur Absensi
```
[Karyawan buka halaman absen]
        ↓
[Scan QR Code]
        ↓
[Validasi token QR] ──✗──→ [Tolak: QR tidak valid / expired]
        ↓ ✓
[Ambil koordinat GPS]
        ↓
[Cek radius lokasi kantor] ──✗──→ [Tolak: Di luar area]
        ↓ ✓
[Simpan absensi + timestamp]
        ↓
[Tampilkan konfirmasi berhasil]
```

### Role & Akses

| Role | Akses |
|---|---|
| **Super Admin** | Kelola semua data, setting lokasi, generate QR |
| **HR / Manager** | Lihat laporan, kelola karyawan & departemen |
| **Karyawan** | Absen, lihat histori absensi sendiri |

---

## 🚀 Cara Instalasi

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

# 6. Jalankan migrasi & seeder
docker compose exec app php artisan migrate --seed

# 7. Install & build frontend (dari host)
npm install
npm run build
```

Akses aplikasi di: `http://localhost:8002`

### Akun Default (Seeder)

| Role | Email | Password |
|---|---|---|
| Super Admin | admin@attendease.com | password |
| HR Manager | hr@attendease.com | password |
| Karyawan | karyawan@attendease.com | password |

---

## 🐳 Docker Services

| Service | Container | Port |
|---|---|---|
| PHP-FPM (App) | ilyra_attendease_app | 9000 |
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

## 🗄️ Struktur Database

```
users               → id, name, email, role, department_id, photo
departments         → id, name, manager_id
attendances         → id, user_id, date, check_in, check_out,
                      lat_in, long_in, lat_out, long_out,
                      qr_token, status
leaves              → id, user_id, type, start_date, end_date, status
office_locations    → id, name, latitude, longitude, radius_meter
```

> 📄 [Lihat ERD lengkap](docs/erd.png)

---

## 🧪 Testing

```bash
# Jalankan semua test
docker compose exec app php artisan test

# Test spesifik
docker compose exec app php artisan test --filter=AttendanceTest

# Dengan coverage
docker compose exec app php artisan test --coverage
```

### Daftar Test Case

| # | Test Case | Status |
|---|---|---|
| 1 | User dapat login dengan kredensial valid | ✅ Pass |
| 2 | User tidak dapat login dengan password salah | ✅ Pass |
| 3 | Karyawan berhasil absen dengan QR & GPS valid | ✅ Pass |
| 4 | Absensi ditolak jika QR expired | ✅ Pass |
| 5 | Absensi ditolak jika lokasi di luar radius | ✅ Pass |
| 6 | Admin dapat generate QR Code harian | ✅ Pass |
| 7 | Laporan dapat diekspor ke Excel | ✅ Pass |
| 8 | Laporan dapat diekspor ke PDF | ✅ Pass |
| 9 | Rate limiting aktif pada endpoint absensi | ✅ Pass |
| 10 | Activity log tercatat saat login/absensi | ✅ Pass |

---

## 🔐 Keamanan

- **CSRF Protection** — Semua form dilindungi token CSRF bawaan Laravel
- **Rate Limiting** — Endpoint absensi dibatasi 5 request/menit per user
- **Input Validation & Sanitasi** — Semua input divalidasi sebelum diproses
- **Role-based Access Control** — Menggunakan Spatie Permission
- **Activity Log** — Setiap aksi penting dicatat
- **QR Token Expiry** — Token QR kadaluarsa otomatis

---

## 📁 Struktur Direktori

```
ilyra-attendease/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/
│   │   │   ├── KaryawanController.php
│   │   │   ├── DepartemenController.php
│   │   │   ├── QRCodeController.php
│   │   │   ├── LaporanController.php
│   │   │   └── LokasiController.php
│   │   └── AttendanceController.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Attendance.php
│   │   ├── Department.php
│   │   ├── Leave.php
│   │   └── OfficeLocation.php
│   └── Services/
│       ├── QRCodeService.php
│       └── GPSValidationService.php
├── docker/
│   └── nginx/conf.d/
│       └── app.conf
├── docs/
│   ├── screenshots/
│   ├── erd.png
│   ├── flowchart.png
│   └── user-manual.pdf
├── docker-compose.yml
├── Dockerfile
└── README.md
```

---

## 📄 Dokumentasi

| Dokumen | Link |
|---|---|
| ERD Database | [docs/erd.png](docs/erd.png) |
| Flowchart Sistem | [docs/flowchart.png](docs/flowchart.png) |
| User Manual | [docs/user-manual.pdf](docs/user-manual.pdf) |

---

## 👤 Author

**Andre Setiawan**
- 🐙 GitHub: [github.com/setiawanandre06](https://github.com/setiawanandre06)

---

## 📝 Lisensi

Project ini menggunakan lisensi [MIT](LICENSE).

---

<p align="center">Dibuat dengan ❤️ menggunakan Laravel 13 & Docker</p>