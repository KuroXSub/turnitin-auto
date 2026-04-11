# Cek Plagiasi KuroSapa - Backend API

<div align="center">

[![GitHub stars](https://img.shields.io/github/stars/KuroXSub/plagiasi-backend?style=for-the-badge)](https://github.com/KuroXSub/plagiasi-backend/stargazers)
[![GitHub forks](https://img.shields.io/github/forks/KuroXSub/plagiasi-backend?style=for-the-badge)](https://github.com/KuroXSub/plagiasi-backend/network)
[![GitHub issues](https://img.shields.io/github/issues/KuroXSub/plagiasi-backend?style=for-the-badge)](https://github.com/KuroXSub/plagiasi-backend/issues)

</div>

Sistem backend REST API tangguh berbasis Laravel 11 untuk aplikasi Cek Plagiasi KuroSapa. Sistem ini bertugas menangani alur penerimaan dokumen secara aman, manajemen antrean, pembatasan kuota (*rate-limiting*), dan panel manajemen administrator terintegrasi. File pengguna diproses secara rahasia menggunakan integrasi penyimpanan *cloud*.

## Fitur Utama
- **Secure Token-Based Upload:** Autentikasi pengunggahan dokumen menggunakan sistem *short-lived token* untuk mencegah eksploitasi dan *spam*.
- **Strict Rate Limiting & Quota:** Pembatasan ketat berbasis IP (maksimal 3 pengiriman sukses/jam) dan kuota global harian sistem.
- **S3 Cloud Storage Integration:** Penyimpanan file mentah dan laporan hasil menggunakan Amazon S3 dengan dukungan *Temporary Expiring URL* untuk keamanan ekstra.
- **Filament Admin Panel:** *Dashboard* antarmuka internal yang elegan untuk mempermudah pekerjaan administrator dalam mereviu dokumen dan mengunggah hasil.
- **Asynchronous Queue Job:** Pengiriman notifikasi sistem di latar belakang agar waktu respon API tetap instan.

## Tech Stack
- **Core Framework:** Laravel 11 (PHP 8.2+)
- **Admin Panel:** Filament PHP v3
- **Database:** MySQL / PostgreSQL
- **Caching & Queue:** Redis
- **Cloud Storage:** Amazon S3 (AWS)

## Instalasi
### Prasyarat
- PHP 8.2+ dan Composer
- Node.js & NPM
- Database MySQL/PostgreSQL & Redis Server berjalan lokal
- AWS S3 Bucket dan kredensial IAM

### Panduan Instalasi
1. Kloning repositori dan masuk ke direktori.
	```bash
	git clone https://github.com/KuroXSub/plagiasi-backend.git
	cd plagiasi-backend
	```

2. Instal seluruh dependensi proyek.
	```bash
	composer install
    npm install && npm run build
	```

3. Setup *environment* dan migrasi database.
    ```bash
    cp .env.example .env
    php artisan key:generate
    # Edit konfigurasi DB, Redis, dan AWS di file .env
    php artisan migrate
    ```

4. Jalankan aplikasi dan peladen antrean latar belakang.
    ```bash
    php artisan serve
    php artisan queue:work
    ```

## Struktur Direktori Utama

```
plagiasi-backend/
├── app/
│   ├── Console/Commands/   # Skrip cron (Reset Quota, dll)
│   ├── Filament/           # Logic panel dashboard Admin
│   ├── Http/Controllers/   # Endpoint REST API (Dokumen, Status, Token)
│   ├── Jobs/               # Asynchronous Background Tasks
│   └── Models/             # Skema dan relasi Database
├── bootstrap/              # Core inisiasi Laravel
├── config/                 # File konfigurasi sistem & AWS
├── database/               # Migrasi, Factory, dan Seeder
├── routes/                 # Definisi rute API dan Web
└── storage/                # File cache lokal dan log sistem
```

## Pengembang

Dikembangkan oleh Qurrota sebagai bagian dari ekosistem KuroSapa Labs.

Website Pengembang: [kuroxsub.my.id](https://kuroxsub.my.id)

GitHub: @KuroXSub