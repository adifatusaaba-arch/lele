# LELE Pak Dedi - Website E-Commerce Jual Beli Ikan Lele

Aplikasi e-commerce jual beli ikan lele berbasis **Laravel**, **MySQL**, **Blade Template Engine**, dan **Bootstrap 5** dengan dukungan dua role utama (**Admin** dan **Customer**).

## Fitur Utama

### Customer
- **Beranda & Katalog Produk**: Pencarian, filter kategori (*Lele Konsumsi*, *Lele Hidup*, *Bibit Lele*, *Lele Ukuran Besar*), filter satuan (`kg` / `ekor`), dan pengurutan harga.
- **Detail Produk**: Galeri foto produk, informasi ukuran ikan, minimal order, stok real-time, dan tombol tambah ke keranjang.
- **Keranjang & Checkout**: Update jumlah item, pilih/tambah alamat pengiriman, pilih metode pengiriman (*Kurir Kolam*, *Ekspedisi Oksigen*, *Ambil Sendiri*), dan pilih metode pembayaran (*Transfer BCA/BRI/Mandiri*, *QRIS*, *COD*).
- **Pembayaran & Pesanan**: Upload bukti transfer, pantau status pesanan (`Pending`, `Menunggu Pembayaran`, `Menunggu Verifikasi`, `Dibayar`, `Diproses`, `Dikirim`, `Selesai`, `Dibatalkan`), konfirmasi pesanan diterima, dan cetak invoice.
- **Profil & Alamat**: Kelola profil, ubah password, dan kelola multi-alamat pengiriman.

### Admin
- **Dashboard**: Ringkasan total produk, total pelanggan, total transaksi, total pendapatan, grafik penjualan 7 hari terakhir (Chart.js), stok menipis, dan pesanan terbaru.
- **Manajemen Kategori & Produk**: CRUD lengkap termasuk upload multi-foto produk dan pengelolaan stok.
- **Manajemen Pesanan, Pembayaran & Pengiriman**: Verifikasi/tolak bukti transfer (dengan pengurangan stok atomik berbasis database transaction), ubah status pesanan, input nomor resi, dan cetak invoice.
- **Manajemen Pelanggan, Laporan Penjualan & Pengaturan**: Filter laporan penjualan per tanggal, daftar produk terlaris, cetak laporan, serta pengaturan rekening bank, ongkos kirim, dan info toko.

## Prasyarat Sistem
- PHP >= 8.3
- Composer
- MySQL / MariaDB

## Panduan Instalasi Lokal

1. **Clone / Masuk ke Direktori Project**
   ```bash
   cd "e:\adiffff\lele ayah"
   ```

2. **Install Dependensi PHP**
   ```bash
   composer install
   ```

3. **Konfigurasi Environment**
   Salin `.env.example` menjadi `.env` (jika belum ada) dan sesuaikan konfigurasi database MySQL:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=lele_pak_dedi
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Generate App Key & Storage Link**
   ```bash
   php artisan key:generate
   php artisan storage:link
   ```

5. **Buat Database MySQL & Jalankan Migrasi + Seeder**
   Pastikan database `lele_pak_dedi` sudah dibuat di MySQL, lalu jalankan:
   ```bash
   php artisan migrate --seed
   ```

6. **Jalankan Server Lokal**
   ```bash
   php artisan serve
   ```
   Buka browser dan akses `http://127.0.0.1:8000`.

## Akun Demo (Hasil Seeder)

| Role | Email | Password |
| :--- | :--- | :--- |
| **Admin** | `admin@lelepakdedi.com` | `password` |
| **Customer** | `budi@example.com` | `password` |
| **Customer** | `siti@example.com` | `password` |
| **Customer** | `agus@example.com` | `password` |

## Menjalankan Pengujian (Automated Tests)
```bash
php artisan test
```
