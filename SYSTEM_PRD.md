# Product Requirements Document (PRD)
## Sistem Manajemen Acara, Desa & Keuangan Bendahara (NH-Finance)

**Dokumen Versi:** 1.0.0  
**Tanggal:** 31 Juli 2026  
**Status:** Approved  
**Platform:** Web Application (Laravel 11 + Laravel Breeze)

---

## 1. Executive Summary

**NH-Finance** (Nusantara Horizon Finance) adalah sistem informasi manajemen terintegrasi berbasis web yang dirancang khusus untuk mengelola data administratif **Desa**, agenda **Acara (Event)**, serta transparansi **Keuangan Acara oleh Bendahara (Admin)**. Sistem ini dilengkapi dengan sistem autentikasi modern (**Laravel Breeze**), manajemen hak akses peran pengguna (**Superadmin** & **Admin/Bendahara**), sistem notifikasi **realtime interaktif**, serta visualisasi data analitik dalam antarmuka yang estetis dan profesional.

---

## 2. Target Pengguna & Roles (Matriks Hak Akses)

Sistem ini mendukung 2 (dua) peran utama dengan pembagian tugas yang jelas:

| Fitur / Modul | Superadmin (`superadmin`) | Admin / Bendahara (`admin_bendahara`) |
| :--- | :---: | :---: |
| **Login & Autentikasi (Breeze)** | ✅ Ya | ✅ Ya |
| **Dashboard Statistik & Visualisasi Chart** | ✅ Ya (Semua Data) | ✅ Ya (Fokus Anggaran & Acara) |
| **CRUD Nama Desa** | ✅ Full CRUD | 👁️ Read Only / View |
| **CRUD Nama Acara** | ✅ Full CRUD | ✅ Full CRUD (Mengelola Acara & Rencana Anggaran) |
| **Modul Keuangan Bendahara (Kas Acara)** | ✅ View / Approve / Full | ✅ Full CRUD (Catat Pemasukan/Pengeluaran) |
| **Manajemen Pengguna & Role** | ✅ Full CRUD | ❌ Tidak Ada Akses |
| **Notifikasi Realtime (Pusat Notifikasi)** | ✅ Menerima semua notifikasi | ✅ Menerima notifikasi terkait acara/keuangan |
| **Export / Laporan PDF & Excel** | ✅ Ya | ✅ Ya (Laporan Keuangan & Acara) |

---

## 3. Arsitektur Sistem & Teknologi

* **Backend Framework:** Laravel 11 / PHP 8.3
* **Authentication:** Laravel Breeze (Blade + Alpine.js + Tailwind CSS / Vanilla CSS custom tokens)
* **Database:** SQLite (Default untuk eksekusi cepat zero-configuration) / MySQL 8+ Compatible
* **Realtime Engine:** Laravel Notification System + Database Broadcast Channels + Frontend Reactive Polling / Event-driven UI (Top Navbar Badge Counter & Interactive Toast Popup)
* **Frontend Components:** 
  * Responsive Glassmorphic Cards & Accent Gradients
  * Interactive Modal Dialogs untuk proses CRUD tanpa reload halaman
  * Chart.js / SVG Dynamic Rendering untuk visualisasi data

---

## 4. Entity Relationship Diagram (ERD) & Skema Database

```mermaid
erDiagram
    USERS {
        id BIGINT PK
        name VARCHAR(255)
        email VARCHAR(255)
        phone VARCHAR(50)
        role ENUM("superadmin", "admin_bendahara")
        avatar VARCHAR(255)
        is_active BOOLEAN
        created_at TIMESTAMP
    }
    DESAS {
        id BIGINT PK
        kode_desa VARCHAR(50)
        nama_desa VARCHAR(255)
        kecamatan VARCHAR(255)
        kepala_desa VARCHAR(255)
        kontak VARCHAR(50)
        populasi INT
        status ENUM("aktif", "nonaktif")
        catatan TEXT
        created_at TIMESTAMP
    }
    ACARAS {
        id BIGINT PK
        desa_id BIGINT FK
        user_id BIGINT FK
        nama_acara VARCHAR(255)
        deskripsi TEXT
        lokasi VARCHAR(255)
        tanggal_mulai DATETIME
        tanggal_selesai DATETIME
        anggaran_rencana DECIMAL(15,2)
        status ENUM("planned", "ongoing", "completed", "cancelled")
        created_at TIMESTAMP
    }
    TRANSAKSI_KEUANGANS {
        id BIGINT PK
        acara_id BIGINT FK
        user_id BIGINT FK
        nomor_transaksi VARCHAR(50)
        tipe ENUM("pemasukan", "pengeluaran")
        kategori VARCHAR(100)
        jumlah DECIMAL(15,2)
        tanggal_transaksi DATE
        keterangan TEXT
        bukti_file VARCHAR(255)
        created_at TIMESTAMP
    }
    NOTIFICATIONS {
        id CHAR(36) PK
        type VARCHAR(255)
        notifiable_type VARCHAR(255)
        notifiable_id BIGINT
        data TEXT
        read_at TIMESTAMP
        created_at TIMESTAMP
    }
    ACTIVITY_LOGS {
        id BIGINT PK
        user_id BIGINT FK
        action VARCHAR(255)
        description TEXT
        ip_address VARCHAR(45)
        created_at TIMESTAMP
    }

    DESAS ||--o{ ACARAS : "memiliki"
    USERS ||--o{ ACARAS : "mengelola"
    ACARAS ||--o{ TRANSAKSI_KEUANGANS : "mencatat arus kas"
    USERS ||--o{ TRANSAKSI_KEUANGANS : "menginput"
    USERS ||--o{ ACTIVITY_LOGS : "melakukan"
```

---

## 5. Spesifikasi Fungsional & Modul

### 5.1. Modul Autentikasi & Keamanan (Breeze Auth + Role Middleware)
* Halaman Login dan Profil Pengguna berbasis **Laravel Breeze**.
* Proteksi rute menggunakan custom middleware `RoleMiddleware` (`role:superadmin` dan `role:superadmin,admin_bendahara`).
* Reset password dan pembaruan informasi profil akun.

### 5.2. Modul CRUD Nama Desa (Village Management)
* **Daftar Desa:** Menampilkan tabel data desa lengkap dengan pencarian cepat (Live Search) berdasarkan nama desa, kode desa, atau kecamatan.
* **Tambah Desa:** Input nama desa, kode unik, penanggung jawab/kepala desa, kontak, serta status aktif/nonaktif.
* **Edit & Hapus Desa:** Modifikasi data desa serta perlindungan integritas agar desa yang memiliki acara aktif tidak dapat dihapus secara sembarangan.

### 5.3. Modul CRUD Nama Acara (Event Management)
* **Daftar Acara:** Menampilkan seluruh agenda acara yang dihubungkan ke desa tertentu.
* **Filter & Pencarian:** Filter berdasarkan Desa, Rentang Tanggal, dan Status Acara (`Planned`, `Ongoing`, `Completed`, `Cancelled`).
* **Alokasi Anggaran:** Penentuan anggaran rencana (`anggaran_rencana`) untuk pemantauan oleh Bendahara.
* **Modal Interaktif:** Form tambah dan edit acara berbasis modal responsif dengan validasi waktu mulai/selesai.

### 5.4. Modul Keuangan Bendahara (Treasury Improvisation)
* **Buku Kas Acara:** Pencatatan setiap transaksi **Pemasukan** dan **Pengeluaran** yang dikaitkan langsung dengan Acara dan Desa.
* **Perhitungan Saldo Otomatis:** Sistem secara otomatis menghitung:
  $$\text{Saldo Akhir} = \sum \text{Pemasukan} - \sum \text{Pengeluaran}$$
* **Indikator Kinerja Anggaran:** Membandingkan total pengeluaran dengan `anggaran_rencana` acara (menampilkan alert warna hijau jika aman, kuning jika mendekati batas, dan merah jika overbudget).
* **Ekspor Laporan:** Ringkasan laporan keuangan dalam format cetak (Print/PDF-ready table).

### 5.5. Modul Notifikasi Realtime (Notification Center)
* **Top Navbar Notification Bell:**
  * Menampilkan *badge counter* merah bernomor untuk notifikasi yang belum dibaca (`unread count`).
  * Dropdown interaktif daftar notifikasi terbaru (misalnya: *"Desa Sukamaju baru saja ditambahkan"*, *"Acara 'Pesta Rakyat 2026' dibuat dengan anggaran Rp 50.000.000"*, *"Transaksi Pengeluaran baru dicatat oleh Bendahara"*).
* **Interactive Toast Alerts:** Pop-up visual di pojok kanan atas yang muncul secara instan saat aktivitas CRUD terjadi.
* **Aksi Cepat:** Tombol *"Tandai semua telah dibaca"* (`Mark all as read`).

---

## 6. Standar Desain Antarmuka (UI/UX Guidelines)

* **Palet Warna Utama:**
  * **Primary (Emerald/Navy):** `#0f172a` (Slate Dark), `#10b981` (Emerald Accent untuk Kas/Keuangan).
  * **Secondary:** `#3b82f6` (Blue untuk Acara/Aktivitas), `#f59e0b` (Amber untuk Peringatan).
* **Tipografi:** Sans-serif modern (Inter / Outfit / system-ui).
* **Responsivitas:** Desain 100% responsif dengan sidebar yang dapat dilipat (*collapsible*) untuk tampilan seluler maupun desktop lebar.
* **Micro-interactions:** Efek transisi halus pada hover kartu statistik, pembukaan modal, dan animasi badge notifikasi.

---

## 7. Kriteria Penerimaan (Acceptance Criteria)

1. **Pengguna Superadmin** dapat melakukan operasi CRUD untuk Desa, Acara, Keuangan, serta mengelola akun pengguna.
2. **Pengguna Admin (Bendahara)** dapat masuk menggunakan kredensialnya dan mencatat arus kas acara serta memperbarui status acara tanpa akses ke manajemen user.
3. **Pusat Notifikasi** memperbarui indikator angka belum dibaca secara langsung saat aksi baru dieksekusi.
4. **Dasbor Statistik** menyajikan total desa, total acara, serta ringkasan kas yang akurat dan estetis.
