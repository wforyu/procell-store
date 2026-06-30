<div align="center">
  <h1>🔧 ProCell Store</h1>
  <p><strong>Toko Online Sparepart & Aksesoris HP</strong></p>
  <p>Laravel 12 · Filament v5 · Laravel Breeze · Alpine.js · Tailwind CSS 4</p>
</div>

---

## ✨ Fitur

### 🛍️ Storefront
- Beranda dengan banner slider, kategori unggulan, flash sale, produk terbaru
- Katalog produk dengan filter kategori & pencarian
- Detail produk dengan gallery gambar, harga, stok, brand
- Keranjang belanja (guest via session, login via user_id)
- Checkout dengan pilihan kurir (JNE, J&T, SiCepat, Ninja) + ongkir realtime
- Pembayaran transfer bank (Mandiri, BCA, BRI) + **Midtrans** (Kartu Kredit, VA, Convenience Store, QRIS, E-Wallet)
- **Guest checkout** tanpa registrasi
- Kupon diskon (percentage / fixed)
- Wishlist produk favorit

### 📦 Manajemen Pesanan
- Upload bukti transfer oleh customer
- Konfirmasi pembayaran oleh admin
- Tracking pengiriman (input kurir + no resi)
- Konfirmasi pesanan diterima
- Riwayat status pesanan lengkap (pending → waiting_confirmation → processing → shipped → completed)

### 🔄 Retur Barang
- Pengajuan retur oleh customer (alasan + foto bukti)
- Setujui / tolak oleh admin
- Notifikasi email ke customer
- Notifikasi database ke admin

### ⚙️ Admin Panel (Filament v5)
- **Katalog**: CRUD Kategori & Produk
- **Persediaan**: Audit stok, Pemasok, Purchase Order
- **Transaksi**: **POS Interface**, CRUD Pesanan + Export CSV, Retur
- **Konten**: Banner slider, Pengeluaran
- **Pelanggan**: Read-only + riwayat pesanan
- **Promo**: Kupon diskon
- **Sistem**: Manajemen User & Role (Super Admin, Stok, Keuangan, Kasir)
- **Pengaturan**: Rekening bank, Dashboard grafik, Pengaturan toko (nama, kontak, jam operasional, flash sale, Midtrans, RajaOngkir, SMTP)

### 🔔 Notifikasi
- Email ke customer setiap perubahan status pesanan
- Email ke customer saat retur disetujui / ditolak
- Notifikasi database ke admin saat customer upload bukti bayar atau ajukan retur

### 🔍 SEO
- Meta tags dinamis (title, description, Open Graph)
- Sitemap XML otomatis (`/sitemap.xml`)
- Schema.org JSON-LD (Organization, BreadcrumbList, Product)

---

## 🚀 Cara Install

### Prasyarat
- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL (via XAMPP / Laragon / dll)

### Langkah-langkah

```bash
# Clone repositori
git clone https://github.com/wforyu/procell-store.git
cd procell-store

# Install dependencies PHP
composer install

# Copy environment
copy .env.example .env

# Generate key
php artisan key:generate

# Install dependensi frontend
npm install
npm run build

# Setup database
# Buat database MySQL bernama procell_store
# Lalu edit .env (DB_DATABASE=procell_store, DB_USERNAME=root, DB_PASSWORD=)

# Jalankan migrasi & seeder
php artisan migrate --seed

# Buat storage link
php artisan storage:link

# Jalankan dev server
composer dev
```

### Akun Default

| Role | Email | Password |
|------|-------|----------|
| Admin (Super Admin) | `admin@procell.com` | `admin123` |
| Customer | `customer@procell.com` | `customer123` |

---

## 🧪 Testing

```bash
composer test
```

Semua test menggunakan SQLite `:memory:` — aman dijalankan kapan saja.

---

## 🐛 Bug Fixes & Workarounds

| Issue | Solusi |
|-------|--------|
| `Class "Filament\Forms\Components\Section" not found` | Ganti import ke `Filament\Schemas\Components\Section` |
| PHP 8.2 `$navigationGroup` fatal error | Hapus properti, gunakan `getNavigationGroup(): string` |
| Duplicate `@php` block tidak dikompilasi | Gabung ke satu blok `@php` |
| JSON-LD `{}` konflik Livewire Blade | Gunakan `json_encode()` dalam `@php` |
| `config:cache` override env phpunit.xml | Selalu `config:clear` sebelum test |

---

## 🗺️ Roadmap

- [x] Konfigurasi SMTP di ManageSettings
- [x] Integrasi RajaOngkir ongkir realtime
- [x] Multi-admin & roles (Spatie Permission)
- [ ] Loyalty points & referral system
- [x] Guest checkout
- [x] Integrasi Midtrans payment gateway
- [ ] Notifikasi WhatsApp

---

## 📄 Lisensi

Hak cipta © 2026 — **ProCell Store**
