# 🏍️ SkensaMoto - Skensa Motor Teaching Factory (TEFA) Workshop ERP & Booking System

**SkensaMoto** adalah sistem Enterprise Resource Planning (ERP) dan manajemen booking servis motor yang dirancang khusus untuk bengkel Teaching Factory (TEFA) di lingkungan Sekolah Menengah Kejuruan (SMK). Aplikasi ini mengintegrasikan alur kerja mekanik sekolah, pengelolaan inventaris suku cadang, kapasitas harian bengkel, hingga manajemen pelanggan dalam satu sistem yang responsif dan modern.

---

## 🛠️ Teknologi & Tools yang Digunakan

Projek ini dibangun menggunakan teknologi modern untuk menjamin performa, keamanan, dan skalabilitas:

*   **Backend & Framework**: PHP 8.2+ & **Laravel 11.x**
*   **Database**: **PostgreSQL** (di-host menggunakan **Supabase** untuk kemudahan skalabilitas)
*   **API Authentication**: **Laravel Sanctum** (digunakan untuk integrasi aman dengan aplikasi Mobile/External)
*   **Frontend**: 
    *   **Laravel Blade** & **Alpine.js** untuk interaktivitas komponen.
    *   **Tailwind CSS v3** dengan tema kustom gelap (*Custom Dark Theme*) untuk UI yang premium dan modern.
*   **Asset & Ikon**: SVG Heroicons (bebas dari penggunaan emoji di dalam antarmuka aplikasi untuk menjaga estetika profesional).

---

## 🌟 Fitur Utama

Sistem ini mendukung pengelolaan multi-pengguna dengan hak akses yang terperinci:

### 1. Sistem Multi-Role (Akses Bertingkat)
*   **Superadmin**: Hak akses penuh termasuk manajemen pengguna, pengaturan tarif bengkel, inventaris, dan konfigurasi sistem.
*   **Admin**: Mengelola jadwal harian, menyetujui/menolak booking, dan memperbarui inventaris barang.
*   **Mekanik**: Memulai pengerjaan servis, menginput kebutuhan suku cadang (*Quotation*), dan menyelesaikan proses servis.
*   **User/Pelanggan**: Mendaftarkan kendaraan, melakukan booking online, memantau progres servis secara real-time, menyetujui estimasi biaya, dan mengunduh invoice.

### 2. Fitur Kendaraan & Booking Pintar
*   **Manajemen Kendaraan**: User dapat mendaftarkan beberapa kendaraan miliknya (menyimpan Plat Nomor, Merk, Tipe, dan Tahun).
*   **Smart Slot Booking**: Sistem menghitung kapasitas sisa bengkel berdasarkan menit kerja harian (`jadwal_harian`). Pendaftaran booking baru akan dibatasi jika kapasitas hari itu telah penuh.
*   **Real-time Progress Tracker**: User dapat memantau status pengerjaan motornya (`pending` $\rightarrow$ `approved` $\rightarrow$ `in_progress` $\rightarrow$ `completed` / `rejected`).

### 3. Inventory & Sistem Invoice Otomatis
*   **Manajemen Stok**: Stok suku cadang di inventaris bengkel akan otomatis terpotong saat mekanik menyelesaikan servis.
*   **Auto-Invoicing**: Sistem otomatis menghitung total biaya servis berdasarkan harga jasa paket yang diambil dan total harga suku cadang yang digunakan.

---

## ⚙️ Panduan Instalasi & Menjalankan Projek

Ikuti langkah-langkah berikut untuk menjalankan projek di komputer lokal Anda:

### Prasyarat (Prerequisites)
Pastikan Anda sudah menginstal program berikut di komputer Anda:
*   [PHP (versi 8.2 ke atas)](https://www.php.net/downloads)
*   [Composer](https://getcomposer.org/download/)
*   [Node.js & NPM](https://nodejs.org/en/download)
*   Database PostgreSQL (atau PostgreSQL via Supabase)

### Langkah-Langkah

1.  **Clone Repository**
    ```bash
    git clone <url-repository-anda>
    cd MotoSkensa
    ```

2.  **Install Dependensi (PHP & Node.js)**
    ```bash
    composer install
    npm install
    ```

3.  **Salin File Environment**
    Salin file `.env.example` menjadi `.env`:
    ```bash
    cp .env.example .env
    ```
    Buka file `.env` dan sesuaikan koneksi database Anda (misalnya ke Supabase PostgreSQL):
    ```env
    DB_CONNECTION=pgsql
    DB_HOST=your-supabase-db-host
    DB_PORT=5432
    DB_DATABASE=your-database-name
    DB_USERNAME=postgres
    DB_PASSWORD=your-database-password
    ```

4.  **Generate Application Key**
    ```bash
    php artisan key:generate
    ```

5.  **Jalankan Migrasi Database beserta Seeders**
    Langkah ini akan membuat tabel-tabel yang dibutuhkan sekaligus mengisi data awal (seperti data admin default, paket servis, dan stok inventaris):
    ```bash
    php artisan migrate --seed
    ```

6.  **Jalankan Server Lokal**
    Jalankan kedua terminal perintah berikut secara bersamaan:
    *   **Terminal 1 (Laravel Server)**:
        ```bash
        php artisan serve
        ```
    *   **Terminal 2 (Vite Server untuk CSS & Assets)**:
        ```bash
        npm run dev
        ```

7.  Akses aplikasi di browser Anda melalui alamat: **`http://localhost:8000`**

---

## 🛜 Panduan Penggunaan API (Menggunakan Postman)

Projek ini dilengkapi dengan endpoint API terproteksi untuk integrasi aplikasi mobile.

### Alur Autentikasi API:
1.  **Register/Login**: Panggil endpoint `POST /api/register` atau `POST /api/login` untuk mendapatkan token akses.
2.  **Set Authorization**: Salin token yang Anda dapatkan, lalu masukkan ke dalam header **Postman** Anda:
    *   Pilih tab **Authorization**.
    *   Pilih Type: **Bearer Token**.
    *   Tempel token Anda di sana.
3.  **Set Accept Header**: Pastikan di tab Headers, tambahkan key `Accept` dengan value `application/json`.

### Contoh Endpoint yang Tersedia:
*   **Registrasi**: `POST /api/register`
*   **Login**: `POST /api/login`
*   **Daftar Kendaraan**: `GET /api/kendaraan` (Mengambil semua kendaraan milik user)
*   **Tambah Kendaraan**: `POST /api/kendaraan` (Mengirim body json: `plat_nomor`, `merk`, `tipe`, `tahun`)
*   **Hapus Kendaraan**: `DELETE /api/kendaraan/{id}` (Menghapus kendaraan berdasarkan ID)
*   **Daftar Booking**: `GET /api/bookings`
*   **Buat Booking**: `POST /api/bookings`
