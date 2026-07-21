# 🏆 Aplikasi Web Karang Taruna

Aplikasi web manajemen **Karang Taruna** dibangun menggunakan framework **Laravel 11**. Aplikasi ini dirancang khusus untuk pengelolaan organisasi kepemudaan tingkat RT/RW, memadukan fungsionalitas administrasi internal dengan beranda publik yang modern, estetis, dan responsif.

---

## 🌟 Fitur Utama & Modul Aplikasi

### 1. 👥 Manajemen Anggota & Pengurus
- Database lengkap pengurus & anggota aktif.
- Visualisasi struktur kepengurusan dengan layout modern, simetris, dan bersih.
- Integrasi data kontak email dan fitur **Web Share API** (untuk membagikan kartu profil pengurus dengan praktis).
- Hubungkan data anggota langsung ke Akun User Pengurus dengan sekali klik.

### 2. 📅 Manajemen Kegiatan & Pengumuman
- Status kegiatan dihitung **secara otomatis** (`Akan Datang`, `Berlangsung`, `Selesai`) berdasarkan perbandingan tanggal pelaksanaan relatif terhadap hari ini.
- Pengelompokan kegiatan secara dinamis berdasarkan kategori (*Lingkungan, Olahraga, Workshop, Sosial*).
- Pengumuman penting terintegrasi dan dapat dipasang di halaman publik dengan toggle layout grid/list.

### 3. 🏆 Modul Lomba & Perlombaan (Baru!)
- Pencatatan daftar lomba yang dikaitkan dengan kegiatan induk (misal: Lomba 17 Agustusan).
- **Otomatisasi Status Lomba:** Status (`Persiapan`, `Berlangsung`, `Selesai`) dihitung secara otomatis berdasarkan tanggal lomba.
- **Manajemen Perlengkapan & Checklist:** Memantau ketersediaan barang inventaris untuk menunjang kebutuhan lomba secara real-time.
- **Daftar Peserta & Podium Juara:** Pendaftaran peserta lomba dan penentuan pemenang (Juara 1, 2, 3) yang akan langsung tampil di halaman publik dengan visualisasi podium trofi yang menarik.

### 4. 📸 Dokumentasi (Galeri Foto Masonry)
- Unggah foto dokumentasi kegiatan secara langsung atau melalui unggahan file **ZIP** (otomatis diekstrak di server).
- **Auto-resize & Compress** gambar pintar menggunakan Intervention Image (GD) untuk menghemat ruang penyimpanan.
- Layout galeri publik menggunakan gaya **Masonry Asimetris** yang sangat premium, filter kategori instan berbasis JavaScript, dan lightbox popup premium.

### 5. 💰 Modul Keuangan (Buku Kas Excel-Like)
- Pencatatan transaksi **Pemasukan** dan **Pengeluaran** lengkap dengan bukti lampiran foto.
- Kalkulasi saldo kas secara otomatis dan real-time.
- Ekspor laporan bulanan ke format **Microsoft Excel (.csv)** dan fitur cetak laporan langsung dari browser (print-friendly).

### 6. 📦 Modul Inventaris & Perlengkapan Barang RT
- Pendataan barang inventaris milik RT (tenda, kursi, sound system, piring, dll) lengkap dengan kategori, lokasi penyimpanan, dan kondisi barang.
- Manajemen peminjaman barang oleh warga dengan pembaruan stok otomatis.
- Deteksi keterlambatan pengembalian barang secara otomatis.

### 7. 🔐 Keamanan & Hak Akses
- Pembatasan hak akses berbasis **Multi-Role** (Super Admin, Pengurus, dsb) dengan pengaturan menu dinamis.
- Perlindungan brute-force login menggunakan throttling (maksimal 5 kali percobaan per menit).
- **Perlindungan Berkas Sensitif:** Dilengkapi berkas `.htaccess` ganda (pada root project dan folder public) untuk memblokir akses langsung ke file `.env` atau folder internal Laravel.
- **Keamanan Informasi Error:** Penggunaan `APP_DEBUG=false` untuk mencegah kebocoran informasi teknis (seperti password database) kepada pengunjung.

---

## ⚙️ Persyaratan Sistem
- PHP >= 8.2 (dengan ekstensi `GD` aktif untuk pemrosesan gambar)
- Composer
- MySQL / MariaDB
- Node.js (untuk kompilasi aset Vite)

---

## 🚀 Panduan Instalasi Lokal

1. **Clone repository:**
   ```bash
   git clone https://github.com/ferryyudha/karang-taruna-kt.git
   cd karang-taruna-kt
   ```

2. **Install dependencies:**
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment:**
   Salin `.env.example` ke `.env`:
   ```bash
   cp .env.example .env
   ```
   Sesuaikan konfigurasi database Anda di file `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=db_karangtaruna
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Generate App Key:**
   ```bash
   php artisan key:generate
   ```

5. **Migrasi Database & Seeders:**
   ```bash
   php artisan migrate --seed
   ```

6. **Link Storage:**
   ```bash
   php artisan storage:link
   ```

7. **Jalankan Server Lokal:**
   ```bash
   php artisan serve
   ```
   Dan jalankan build asset Vite di terminal terpisah:
   ```bash
   npm run dev
   ```

---

## 🔑 Informasi Login Awal
- **Halaman Login:** `/login` atau klik tombol **Login** di pojok kanan atas beranda.
- **Email Akun Bawaan:**
  - **Super Admin:** `admin@karangtaruna.com`
  - **Pengurus:** `pengurus@karangtaruna.com`
- **Password Akun Bawaan:** 
  Password dibuat secara acak demi keamanan setiap kali perintah `php artisan migrate --seed` dijalankan dan **hanya ditampilkan sekali pada terminal**. Catat password tersebut dari output terminal saat proses seeding selesai dilakukan.
- ⚠️ **Keamanan:** Jangan pernah menyimpan password asli di dalam berkas dokumentasi ini karena repositori ini bersifat publik.
