# PRD.md

# ERP Bendahara Event Desa

**Versi:** 1.0.0
**Status:** MVP
**Platform:** Web Application
**Framework:** Laravel 12
**Database:** PostgreSQL / MySQL

---

# 1. Latar Belakang

Pelaksanaan kegiatan HUT Kemerdekaan RI tingkat desa melibatkan banyak transaksi keuangan, sponsor, inventaris, dan administrasi yang umumnya masih dilakukan menggunakan buku tulis atau spreadsheet.

Akibatnya sering terjadi:

* Kesalahan pencatatan
* Bukti transaksi tercecer
* Sulit mengetahui saldo kas secara real-time
* Penyusunan LPJ memakan waktu lama
* Kurangnya transparansi kepada panitia dan masyarakat

ERP Bendahara Event Desa dibangun untuk mendigitalisasi seluruh proses administrasi dan keuangan dalam satu sistem yang mudah digunakan.

---

# 2. Tujuan

* Mengelola keuangan event secara terpusat
* Mempermudah bendahara dalam pencatatan transaksi
* Mempermudah penyusunan laporan
* Menyediakan transparansi keuangan
* Mengurangi kesalahan pencatatan manual

---

# 3. Target Pengguna

* Ketua Panitia
* Wakil Ketua
* Bendahara
* Sekretaris
* Divisi Acara
* Divisi Perlengkapan
* Divisi Konsumsi
* Divisi Humas
* Divisi Dokumentasi
* Auditor Internal

---

# 4. Ruang Lingkup MVP

Sistem hanya digunakan untuk **satu event**.

Contoh:

> HUT Kemerdekaan RI Desa Sukamaju Tahun 2026

Belum mendukung:

* Multi Event
* Multi Organisasi
* SaaS
* Multi Tenant

Namun struktur aplikasi harus mudah dikembangkan ke arah tersebut.

---

# 5. Role User

## Administrator

Hak akses penuh terhadap seluruh sistem.

---

## Ketua Panitia

Dapat melihat seluruh data dan melakukan approval transaksi.

---

## Bendahara

Mengelola:

* Kas
* Pemasukan
* Pengeluaran
* Sponsor
* Donatur
* Vendor
* Laporan

---

## Sekretaris

Mengelola:

* Proposal
* Surat
* Dokumen
* Jadwal
* Notulen

---

## Divisi

Setiap divisi hanya dapat mengakses data yang berkaitan dengan tugasnya.

---

# 6. Modul Sistem

## Dashboard

Menampilkan:

* Saldo Kas
* Total Pemasukan
* Total Pengeluaran
* Budget Tersisa
* Sponsor Aktif
* Donatur
* Vendor
* Jumlah Lomba
* Aktivitas Terbaru

---

## Struktur Panitia

Data:

* Nama
* Jabatan
* Nomor HP
* Divisi
* Status

---

## Anggaran (RAB)

Fitur:

* Membuat anggaran
* Mengubah anggaran
* Monitoring realisasi
* Persentase penggunaan budget

Field:

* Nama Item
* Kategori
* Budget
* Realisasi
* Selisih

---

## Pemasukan

Kategori:

* Sponsor
* Donasi
* Kas Desa
* Penjualan Kupon
* Bazaar
* Tiket

Field:

* Tanggal
* Nominal
* Kategori
* Metode Pembayaran
* Bukti
* Keterangan

---

## Pengeluaran

Kategori:

* Konsumsi
* Hadiah
* Banner
* Dekorasi
* Sound System
* Transportasi
* Honor
* Dokumentasi

Field:

* Vendor
* Nominal
* Bukti
* Approval
* Status

---

## Sponsor

Data:

* Nama
* Perusahaan
* Kontak
* Nominal
* Status

Status:

* Prospek
* Negosiasi
* Deal
* Lunas

---

## Donatur

Data:

* Nama
* Nominal
* Tanggal
* Metode Pembayaran

---

## Vendor

Data:

* Nama Vendor
* Kategori
* Kontak
* Alamat

---

## Inventaris

Data:

* Nama Barang
* Jumlah
* Kondisi
* Lokasi
* Keterangan

Status:

* Baik
* Dipinjam
* Rusak
* Hilang

---

## Jadwal

Menampilkan timeline kegiatan:

* Persiapan
* Technical Meeting
* Lomba
* Pentas Seni
* Pembagian Hadiah

---

## Dokumen

Upload:

* Proposal
* Surat
* LPJ
* Invoice
* Kwitansi
* Dokumentasi

---

## Surat

Generate otomatis:

* Surat Sponsor
* Surat Permohonan Dana
* Surat Peminjaman
* Surat Tugas

Export PDF.

---

## Laporan

Jenis laporan:

* Buku Kas
* Cash Flow
* Pemasukan
* Pengeluaran
* Sponsor
* Donatur
* Budget vs Realisasi

Export:

* PDF
* Excel

---

# 7. Workflow

## Pemasukan

Input Data

↓

Verifikasi

↓

Masuk Buku Kas

↓

Laporan

---

## Pengeluaran

Pengajuan

↓

Approval Ketua

↓

Pembayaran

↓

Masuk Buku Kas

↓

Laporan

---

# 8. Dashboard

Widget:

* Total Saldo
* Cash Flow
* Pengeluaran Hari Ini
* Sponsor Aktif
* Donatur Terbaru
* Progress Budget
* Aktivitas Terbaru

---

# 9. Hak Akses

Gunakan **Spatie Laravel Permission**.

Permission dasar:

* View
* Create
* Update
* Delete
* Approve
* Export
* Print

---

# 10. Non Functional Requirement

## Performance

* Halaman < 2 detik
* Mendukung minimal 50 pengguna aktif
* Pagination pada seluruh tabel

---

## Security

* Authentication Laravel
* CSRF Protection
* Password Hashing
* Audit Log aktivitas
* Role Based Access Control (RBAC)

---

## Backup

* Backup database manual
* Export seluruh data ke Excel

---

## Responsive

Harus berjalan baik pada:

* Desktop
* Tablet
* Smartphone

---

# 11. Teknologi

Backend

* Laravel 12
* PHP 8.4

Frontend

* Blade
* Tailwind CSS
* Alpine.js

Database

* PostgreSQL atau MySQL

Authentication

* Laravel Breeze

Permission

* Spatie Laravel Permission

Storage

* Laravel Storage

PDF

* DomPDF

Excel

* Laravel Excel

---

# 12. Struktur Menu

Dashboard

Anggaran (RAB)

Keuangan

* Pemasukan
* Pengeluaran
* Buku Kas

Sponsor

Donatur

Vendor

Inventaris

Struktur Panitia

Jadwal

Dokumen

Surat

Laporan

Pengaturan

---

# 13. Future Roadmap

Versi berikutnya akan mendukung:

* Multi Event
* Multi Organisasi
* Portal Transparansi Publik
* QRIS Donasi
* QR Code Absensi Panitia
* WhatsApp Notification
* Digital Signature
* Mobile Application
* SaaS Multi-Tenant

---

# 14. Definisi Selesai (Definition of Done)

Produk dianggap siap digunakan apabila:

* Semua modul MVP berjalan dengan baik.
* Hak akses sesuai peran pengguna.
* Seluruh transaksi tercatat pada Buku Kas.
* Laporan dapat diekspor ke PDF dan Excel.
* Tampilan responsif pada desktop dan perangkat mobile.
* Tidak terdapat bug kritis pada alur utama (pemasukan, pengeluaran, anggaran, dan pelaporan).
