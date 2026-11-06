# Book-Store-USK-TO-1

Aplikasi Web Toko Buku USK (BukuKita USK) berbasis framework Laravel 12 dan PHP 8.5 yang dikembangkan untuk persiapan Uji Sertifikasi Kompetensi (USK) Siswa SMK RPL Skema Junior Web Developer.

## Fitur Utama

### 1. Pelanggan (User)
- Landing Page dengan katalog buku terbaru dan informasi toko.
- Registrasi akun (wajib domain @gmail.com) dan login autentikasi.
- Katalog Buku dengan pencarian multi-kolom (judul, penulis, penerbit) dan filter kategori.
- Detail buku dan rekomendasi buku terkait.
- Keranjang belanja dinamis (tambah, kurangi kuantitas, hapus item, validasi stok real-time).
- Checkout pesanan dengan kalkulasi otomatis dan pilihan metode pembayaran (Transfer Bank PAD / COD).
- Riwayat pesanan dan halaman faktur / struk pembelian unik (#ORD-...).
- Unggah bukti transfer pembayaran dengan status tetap Menunggu Konfirmasi sebelum diverifikasi admin.
- Fitur pembatalan pesanan oleh user dengan pengembalian stok otomatis.
- Halaman Contact Admin untuk mengirim pesan atau pertanyaan dan melihat balasan admin.

### 2. Pengelola (Admin)
- Dashboard admin dengan ringkasan statistik (total buku, pesanan baru, pengguna terdaftar, pesan belum dibaca).
- Proteksi AdminMiddleware dengan halaman resmi HTTP 403 Unauthorized jika diakses oleh non-admin.
- Manajemen Master Kategori (CRUD).
- Manajemen Master Buku (CRUD, unggah gambar sampul, validasi judul unik/tidak boleh kembar, dan proteksi hapus jika buku pernah dipesan).
- Manajemen Transaksi Pesanan (filter status pesanan & pembayaran, tinjau bukti transfer, dan pembaruan status transaksi).
- Manajemen Pengguna terdaftar.
- Manajemen Pesan Masuk Pelanggan (membaca dan membalas pesan di tabel database `pesan_kontaks`).

## Spesifikasi Teknologi
- Framework: Laravel 12
- Bahasa: PHP 8.5
- Basis Data: MySQL
- Template Engine: Blade
- Styling: Custom CSS responsif (Vanilla CSS)
- Keamanan: CSRF Protection, XSS Sanitization, BCRYPT Hashing, Rate Limiter Login, Role-Based Access Control (RBAC).

## Panduan Instalasi Lokal

1. Salin repositori:
```bash
git clone https://github.com/Fadhil-byte/Book-Strore-USK-TO-1.git
cd Book-Strore-USK-TO-1
```

2. Pasang dependensi PHP:
```bash
composer install
```

3. Salin berkas lingkungan dan generate application key:
```bash
cp .env.example .env
php artisan key:generate
```

4. Sesuaikan konfigurasi database di berkas `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=usk_latihan_3
DB_USERNAME=root
DB_PASSWORD=
```

5. Jalankan migrasi dan seeding data:
```bash
php artisan migrate --seed
```

6. Jalankan server lokal:
```bash
php artisan serve
```

## Akun Demo
- **Administrator:** `admin@tokobuku.usk` / `admin123`
- **Pelanggan:** `user@gmail.com` / `user1234`
