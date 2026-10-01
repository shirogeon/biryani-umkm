# Dapur Nasi Biryani Berkah 🥘

Aplikasi pemesanan terintegrasi dan panel manajemen (Kasir & Dapur) yang dirancang khusus untuk UMKM Dapur Nasi Biryani Berkah. 
Menampilkan antarmuka pelanggan bergaya editorial premium (bebas *AI slop*) dengan animasi interaktif, serta dashboard admin yang fungsional untuk mengelola pesanan secara *real-time*.

## Fitur Utama

### 🛒 Halaman Pelanggan (Customer Frontend)
- **Katalog Menu Interaktif:** Desain asimetris dan editorial yang menonjolkan visual produk.
- **Storytelling Animasi:** Menggunakan GSAP ScrollTrigger untuk interaksi *stop-motion* murni ala *Bite Toothpaste Bits*.
- **Opsi Layanan Fleksibel:** Mendukung pesanan *Dine-in* (Makan di tempat), *Takeaway* (Bawa Pulang), dan *Delivery* (Pesan Antar).
- **Checkout & Keranjang Cerdas:** Sinkronisasi keranjang *real-time* (menggunakan LocalStorage) dan kalkulasi otomatis ongkos kirim.
- **Pelacakan Mandiri & Struk:** Pelanggan dapat melacak status masakan dapur dan mencetak nota struk digital.
- **Integrasi WhatsApp API:** Konfirmasi pesanan langsung terhubung ke WhatsApp kasir secara otomatis.

### 🔐 Panel Pengelola (Admin Dashboard)
- **Manajemen Pesanan (Live):** Memproses pesanan dari status *Menunggu* -> *Dimasak* -> *Diantar/Disajikan* -> *Selesai*.
- **Katalog & Kategori:** Menambah, mengubah, dan menonaktifkan menu atau stok makanan.
- **Laporan Penjualan:** Ringkasan pendapatan dan produk terlaris.
- **Pengaturan Toko:** Mengubah nomor WhatsApp tujuan, rekening pembayaran, ongkos kirim, dll.

## Teknologi yang Digunakan
- **Backend:** Laravel 9 (PHP 8.0+)
- **Database:** MySQL
- **Frontend & Styling:** Tailwind CSS 3
- **Animasi:** GSAP & ScrollTrigger
- **Ikon:** FontAwesome

---

## Panduan Instalasi Lokal

Ikuti langkah-langkah di bawah ini untuk menjalankan aplikasi ini di komputer Anda (menggunakan XAMPP/Laragon/Valet).

### 1. Clone Repositori
```bash
git clone https://github.com/shirogeon/biryani-umkm.git
cd biryani-umkm
```

### 2. Instalasi Dependensi (Composer)
Pastikan Anda sudah menginstal [Composer](https://getcomposer.org/).
```bash
composer install
```

### 3. Konfigurasi Database (.env)
Copy file `.env.example` menjadi `.env`.
```bash
cp .env.example .env
```
Buka file `.env` dan sesuaikan nama database Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=umkm_biryani  # Pastikan Anda sudah membuat database kosong dengan nama ini
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate App Key & Migrasi Database
Jalankan perintah ini untuk melakukan inisiasi *key* Laravel, membuat struktur tabel, dan mengisi data awal (*seeding*):
```bash
php artisan key:generate
php artisan migrate:fresh --seed
```
*(Catatan: Proses seeding akan otomatis membuatkan akun admin, daftar menu awal, dan pengaturan toko).*

### 5. Jalankan Aplikasi
```bash
php artisan serve
```
Aplikasi kini dapat diakses di browser:
- Halaman Pelanggan: **http://127.0.0.1:8000**
- Halaman Admin: **http://127.0.0.1:8000/admin**

---

## Kredensial Default Admin
Gunakan akun ini untuk masuk ke Panel Admin setelah proses instalasi dan migrasi selesai:
- **Email:** `admin@biryani.com`
- **Password:** `password123`

---

## Kontribusi & Lisensi
Proyek ini dibuat sebagai solusi sistem pemesanan UMKM (*open-source* / *free to use*). Anda bebas melakukan *fork*, memodifikasi, dan menggunakannya untuk bisnis kuliner Anda sendiri.

*Didesain dan dikembangkan dengan ❤️ untuk kemajuan UMKM Kuliner.*
