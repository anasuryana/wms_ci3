# 📦 wms_ci3 – Warehouse Management System (WMS) dengan CodeIgniter 3

Sistem manajemen gudang berbasis web yang dibangun menggunakan framework CodeIgniter 3. Dirancang untuk mempermudah pengelolaan stok, penerimaan barang, dan pengiriman barang di gudang.

---

## 🚀 Fitur Utama

- Manajemen stok barang
- Penerimaan dan pengiriman barang
- Laporan stok dan transaksi
- Antarmuka pengguna berbasis web yang responsif
- Autentikasi pengguna dengan level akses berbeda

---

## 🛠️ Prasyarat

Sebelum memulai, pastikan kamu memiliki:

- PHP <=8.0
- MySQL 5.6 atau versi lebih tinggi
- SQL Server
- Composer (untuk manajemen dependensi)
- Web server seperti Apache atau Nginx

---

## 📥 Instalasi

1. Clone repositori ini ke direktori lokal:

   ```bash
   git clone https://github.com/
2. Salin file .env.example menjadi .env dan sesuaikan konfigurasi database dengan data yang sesuai:

   ```bash
   cp .env.example .env
   
3.  Buka file .env dan sesuaikan konfigurasi seperti database host, username, password, dan nama database sesuai dengan pengaturan lokal kamu.
4. Instal dependensi menggunakan Composer:

    ```bash
    composer install
5. Impor skema database dari file `wms.sql`:

    -   File `wms.sql` sudah disediakan dalam repositori ini.

    -   Gunakan tools seperti MSSQL Management Studio.

6. Jalankan aplikasi:
Akses aplikasi melalui browser di http://localhost/wms_ci3. Pastikan web server (seperti Apache atau Nginx) sudah dikonfigurasi untuk menunjuk ke direktori public/ di dalam folder proyek

