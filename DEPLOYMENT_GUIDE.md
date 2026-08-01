# 🚀 Panduan Deploy Production (Production Deployment Guide)
**Nama Sistem:** NH FINANCIAL EVENT MANAGEMENT  
**Versi:** 1.0 (HUT RI ke-79 Executive ERP)  
**Pengembang:** nhmedia technology (Harun Ar Rasyid - `https://harunarrasyid.vercel.app`)  
**Stack Teknologi:** Laravel 11/12, PHP 8.2+, MySQL 8.0 / PostgreSQL / SQLite, Vite (Vanilla CSS Neumorphism), Chart.js  

---

## 📋 1. Checklist Persiapan Pra-Deploy (Pre-Flight Checklist)

Sebelum memindahkan kode ke server produksi, pastikan seluruh item berikut telah siap:

- [x] **1. Build Aset Frontend:** Aset Vite (`resources/css`, `resources/js`) telah dikompilasi menggunakan perintah `npm run build` (tersimpan di folder `public/build`).
- [x] **2. Konfigurasi Email SMTP Gmail:** Pengaturan SMTP pada file `.env` telah disiapkan dan diuji untuk fitur reset password, OTP, serta notifikasi.
- [x] **3. Skema Database & Migration:** Seluruh file migrasi (`database/migrations`) telah mendukung relasi `hasOne` (Bendahara -> Desa) dan `hasMany` (Superadmin -> Desa).
- [x] **4. Seeder Production Ready:** `DatabaseSeeder.php` siap diubah ke mode bersih atau dijalankan dengan data default admin dan desa awal.
- [x] **5. Pengujian Automasi:** Suite pengujian PHPUnit (`php artisan test`) telah lolos 100% (**25 / 25 PASSED**).

---

## ⚙️ 2. Konfigurasi Variabel Lingkungan (`.env` Production)

Salin contoh di bawah ini ke file `.env` pada server produksi Anda:

```env
APP_NAME="NH FINANCIAL EVENT MANAGEMENT"
APP_ENV=production
APP_KEY=base64:GENERATE_VIA_PHP_ARTISAN_KEY_GENERATE=
APP_DEBUG=false
APP_URL=https://namadomainanda.com

APP_LOCALE=id
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=id_ID

LOG_CHANNEL=stack
LOG_LEVEL=error

# Pilih Koneksi Database (MySQL / MariaDB / PostgreSQL)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nhfinance_production
DB_USERNAME=user_database_anda
DB_PASSWORD="password_database_anda"

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=true

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=public
QUEUE_CONNECTION=database

CACHE_STORE=database

# KONFIGURASI EMAIL SMTP GMAIL
MAIL_MAILER=smtp
MAIL_SCHEME=null
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=harunrasyidar1@gmail.com
MAIL_PASSWORD="mptj tcul nxrc jirx"
MAIL_FROM_ADDRESS="harunrasyidar1@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"
```

> [!IMPORTANT]
> **Peringatan Keamanan:** Pastikan nilai `APP_DEBUG=false` pada lingkungan production. Mengaktifkan debug di production dapat mengekspos kredensial database dan SMTP Anda kepada pihak luar!

---

## 🖥️ 3. Langkah Deploy ke VPS (Ubuntu 22.04 / Nginx / PHP-FPM)

### Langkah 1: Kloning Repositori & Install Dependensi
```bash
# Kloning dari Git repositori Anda
git clone https://github.com/username/nhfinance.git /var/www/nhfinance
cd /var/www/nhfinance

# Install dependensi PHP (Tanpa dev packages untuk penghematan memori)
composer install --optimize-autoloader --no-dev

# Install & Build Aset Frontend
npm ci
npm run build
```

### Langkah 2: Atur Hak Akses Folder (Permissions)
```bash
# Atur kepemilikan kepada user web server (www-data)
sudo chown -R www-data:www-data /var/www/nhfinance
sudo chmod -R 775 /var/www/nhfinance/storage
sudo chmod -R 775 /var/www/nhfinance/bootstrap/cache
```

### Langkah 3: Setup `.env`, Migration & Storage Link
```bash
# Salin template .env.example menjadi .env lalu sesuaikan
cp .env.example .env
nano .env

# Generate Application Key
php artisan key:generate --force

# Tautkan direktori public/storage untuk foto Bukti Struk/Transfer
php artisan storage:link

# Jalankan migrasi database di production TANPA data dummy (menggunakan ProductionSeeder)
php artisan migrate --force --seed --seeder=ProductionSeeder
```

### Langkah 4: Optimasi Caching Production
```bash
# Jalankan optimasi gabungan Laravel (Config, Routes, Events, Views)
php artisan optimize

# Atau secara manual:
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### Langkah 5: Konfigurasi Virtual Host Nginx (`/etc/nginx/sites-available/nhfinance`)
```nginx
server {
    listen 80;
    listen [::]:80;
    server_name namadomainanda.com www.namadomainanda.com;
    root /var/www/nhfinance/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```
Aktifkan konfigurasi, tes sintaks Nginx, dan restart web server:
```bash
sudo ln -s /etc/nginx/sites-available/nhfinance /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

---

## 🌐 4. Langkah Deploy ke Shared Hosting (cPanel / CyberPanel)

Bagi organisasi desa atau panitia yang menggunakan Shared Hosting standar:

1. **Unggah Proyek ke Direktori di Luar `public_html`**:
   - Letakkan folder proyek Laravel Anda di `home/useranda/nhfinance/` (bukan di dalam `public_html`).
2. **Hubungkan Folder Public ke `public_html`**:
   - Pindahkan seluruh isi folder `public/` milik Laravel ke dalam folder `public_html/` milik cPanel Anda.
3. **Sesuaikan Path di File `public_html/index.php`**:
   Buka `public_html/index.php` dan sesuaikan path menuju `vendor/autoload.php` serta `bootstrap/app.php`:
   ```php
   // Ubah dari:
   require __DIR__.'/../vendor/autoload.php';
   $app = require_once __DIR__.'/../bootstrap/app.php';

   // Menjadi:
   require __DIR__.'/../nhfinance/vendor/autoload.php';
   $app = require_once __DIR__.'/../nhfinance/bootstrap/app.php';
   ```
4. **Buat Symlink untuk Storage di cPanel**:
   - Gunakan fitur *Terminal* di cPanel atau buat skrip PHP sederhana di `public_html/link.php`:
     ```php
     <?php
     symlink('/home/useranda/nhfinance/storage/app/public', '/home/useranda/public_html/storage');
     echo "Storage Linked Successfully!";
     ```
   - Buka `https://namadomainanda.com/link.php` sekali, lalu hapus file `link.php` demi keamanan.
5. **Jalankan Migration & Seeder via Terminal cPanel (Tanpa Data Dummy)**:
   ```bash
   cd /home/useranda/nhfinance
   php artisan migrate --force --seed --seeder=ProductionSeeder
   php artisan optimize
   ```

---

## 🛡️ 5. Checklist Keamanan & Pemeliharaan Pasca-Deploy

- [x] **Sertifikasi HTTPS / SSL:** Gunakan *Let's Encrypt Certbot* (`sudo certbot --nginx -d namadomainanda.com`) agar komunikasi protokol tersandi HTTPS.
- [x] **Batasan Ukuran Upload File (PHP.ini):** Pastikan `upload_max_filesize = 10M` dan `post_max_size = 12M` untuk mendukung unggahan foto struk berkualitas tinggi.
- [x] **Arsip Cadangan (Backup Database):** Jadwalkan *cron job* harian/mingguan untuk membuat cadangan database produksi dan file di `storage/app/public`.
- [x] **Verifikasi Branding & Kontak Developer:** Tautan branding `nhmedia technology` di sidebar mengarah ke `https://harunarrasyid.vercel.app` sebagai rujukan bantuan teknis pengembang.
