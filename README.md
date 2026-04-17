# E-Reseller

Sistem **self-ordering** berbasis web yang dirancang untuk memudahkan pelanggan melakukan pemesanan produk secara mandiri — mulai dari browsing katalog, checkout, hingga pelacakan status pesanan.

> ?? **Status:** Frontend Only — Backend / API belum diimplementasikan.

---

## ?? Fitur

- ??? **Katalog Produk** — Tampilkan produk lengkap dengan foto, nama, harga, stok, dan deskripsi
- ?? **Pencarian & Filter** — Search bar dan filter kategori (skincare, bodycare, dll.)
- ?? **Keranjang Belanja** — Tambah, ubah jumlah, hapus produk, dan lihat total harga
- ?? **Formulir Pemesanan** — Input data diri, pilihan metode pengiriman (Ambil di Toko / Dikirim) dan pembayaran (Transfer / COD)
- ? **Konfirmasi Pembayaran** — Tombol "Saya sudah membayar" tanpa perlu upload bukti transfer
- ?? **Pelacakan Pesanan** — Pantau status: Diterima ? Diproses ? Dikemas ? Siap Diambil/Dikirim
- ?? **Autentikasi** — Halaman login sebelum mengakses website
- ??? **Panel Admin** — Kelola pesanan, verifikasi pembayaran, update status, dan manajemen produk & stok

---

## ??? Halaman yang Tersedia

| Halaman | Deskripsi |
|---|---|
| `landing` | Halaman utama / beranda |
| `catalog` | Daftar semua produk |
| `product-detail` | Detail produk (manfaat, cara pakai, harga, stok) |
| `cart-checkout` | Keranjang belanja & form checkout |
| `order-confirmation` | Konfirmasi dan detail pembayaran |
| `order-tracking` | Pelacakan status pesanan |
| `auth` | Login pelanggan |
| `admin` | Panel admin |

---

## ??? Tech Stack

- **Framework:** Laravel 11
- **Frontend:** Blade Templating, Tailwind CSS (via Vite)
- **Build Tool:** Vite

---

## ?? Instalasi & Menjalankan Proyek

```bash
# 1. Clone repository
git clone https://github.com/ghilmanfz/ereseller.git
cd ereseller

# 2. Install dependensi PHP
composer install

# 3. Install dependensi Node.js
npm install

# 4. Salin file environment
cp .env.example .env

# 5. Generate app key
php artisan key:generate

# 6. Jalankan build frontend
npm run dev

# 7. Jalankan server
php artisan serve
```

Akses di: `http://localhost:8000`

---

## ?? Struktur Folder Utama

```
resources/views/
+-- layouts/          # Layout utama (header, footer, dll.)
+-- components/       # Komponen reusable (card, navbar, dll.)
+-- pages/
    +-- auth/         # Halaman login
    +-- admin/        # Panel admin
    +-- landing.blade.php
    +-- catalog.blade.php
    +-- product-detail.blade.php
    +-- cart-checkout.blade.php
    +-- order-confirmation.blade.php
    +-- order-tracking.blade.php
```

---

## ?? Catatan

- Program ini **tidak** menyediakan fitur pembatalan pesanan secara mandiri oleh pelanggan.
- Verifikasi pembayaran dilakukan **manual** oleh admin melalui pengecekan mutasi.
- Pemilihan jasa ekspedisi ditentukan sepenuhnya oleh penjual/admin.

---

## ?? Author

**ghilmanfz** — [github.com/ghilmanfz](https://github.com/ghilmanfz)
