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
- **Live Search** — dropdown saran real-time (nama, brand, harga, gambar)
- Detail produk dengan gallery gambar, harga, stok, brand, **ulasan rating**
- **Frequently Bought Together** — produk yang sering dibeli bersamaan
- **Compare Products** — bandingkan hingga 4 produk side-by-side
- **Chat / Live Chat** — chat real-time dengan admin (Alpine.js polling 5 detik)
- Keranjang belanja (guest via session, login via user_id)
- Checkout dengan pilihan kurir (JNE, J&T, SiCepat, Ninja) + ongkir realtime
- Pembayaran transfer bank (Mandiri, BCA, BRI) + **Midtrans** (Kartu Kredit, VA, Convenience Store, QRIS, E-Wallet)
- **Guest checkout** tanpa registrasi
- Kupon diskon (percentage / fixed) + **Poin loyalitas** (earn & redeem)
- Wishlist produk favorit
- Halaman statis (Tentang Kami, Kebijakan Privasi)

### 📦 Manajemen Pesanan
- Upload bukti transfer oleh customer
- Konfirmasi pembayaran oleh admin (dengan notifikasi WA + email)
- Tracking pengiriman (input kurir + no resi)
- Konfirmasi pesanan diterima
- **Ulasan produk** setelah pesanan selesai
- Riwayat status pesanan lengkap (pending → waiting_confirmation → processing → shipped → completed)

### 🔄 Retur Barang
- Pengajuan retur oleh customer (alasan + foto bukti)
- Setujui / tolak oleh admin
- Notifikasi email + WA ke customer
- Notifikasi database + WA ke admin

### 💰 Refund Management
- Terpisah dari retur — workflow pending → approved → processed → completed / rejected
- Metode refund: transfer / tunai

### ⚙️ Admin Panel (Filament v5)
- **Katalog**: CRUD Kategori, Produk & Ulasan Produk
- **Persediaan**: Audit stok, Pemasok, Purchase Order (draft→ordered→received→cancelled)
- **Transaksi**: **POS Interface** + CRUD Pesanan + Export CSV, Retur, **Refund**
- **Laporan**: **Laba Rugi & Arus Kas** (filter bulan/tahun, CSV export)
- **Konten**: Banner slider, Halaman Statis, Pengeluaran
- **Pelanggan**: Read-only + riwayat pesanan
- **Promo**: Kupon diskon
- **Layanan Pelanggan**: **Chat** — lihat percakapan + balas + tutup
- **Sistem**: Manajemen User & Role (Super Admin, Stok, Keuangan, Kasir), **Audit Log Admin**, **Backup Database**, **Broadcast WA/Email**
- **Pengaturan**: Rekening bank, Dashboard grafik & analitik (Top Products, Slow Moving, Loyal Customers), Pengaturan toko (nama, kontak, jam, flash sale, SEO, Midtrans, RajaOngkir, SMTP, Fonnte, Loyalty)

### 📊 Dashboard Analytics
- 7 stat cards: Revenue, Expenses, Gross Profit (+margin%), AOV, Conversion Rate, Total Products, Low Stock
- Grafik pendapatan 30 hari
- Grafik pergerakan stok
- Top 10 produk terlaris
- Slow-moving products (stok tinggi, penjualan rendah)
- Top 10 pelanggan setia

### 🔔 Notifikasi
- **Email** ke customer setiap perubahan status pesanan & retur
- **WhatsApp** (Fonnte) ke customer untuk status pesanan & retur
- **Database notification** ke admin saat customer upload bukti bayar, ajukan retur, atau kirim chat
- **Broadcast** WhatsApp/Email massal ke customer

### 🔍 SEO
- Meta tags dinamis (title, description, keywords, Open Graph)
- Sitemap XML otomatis (`/sitemap.xml`) — mencakup products, categories, pages
- Schema.org JSON-LD (Organization, BreadcrumbList, Product)
- Robots meta (index, follow)

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

Semua test menggunakan SQLite `:memory:` — aman dijalankan kapan saja. `composer test` otomatis `config:clear` sebelum jalan.

---

## 🐛 Bug Fixes & Workarounds

| Issue | Solusi |
|-------|--------|
| `Class "Filament\Forms\Components\Section" not found` | Ganti import ke `Filament\Schemas\Components\Section` |
| PHP 8.2 `$navigationGroup` fatal error | Hapus properti, gunakan `getNavigationGroup(): string` |
| Duplicate `@php` block tidak dikompilasi | Gabung ke satu blok `@php` |
| JSON-LD `{}` konflik Livewire Blade | Gunakan `json_encode()` dalam `@php` |
| `config:cache` override env phpunit.xml | Selalu `config:clear` sebelum test |
| `payment_method` hanya tampil `-` selain `bank_transfer` | Tambah mapping `cash` & `midtrans` di tabel & export |
| `sum('total')` not found di `purchase_orders` | Kolom namanya `total_amount`, bukan `total` |
| GROUP BY `only_full_group_by` di widget | Gunakan subquery bukan LEFT JOIN + GROUP BY |

---

## 🗺️ Status Fitur

### ✅ Phase 1 — Dasar
- [x] Scaffolding Laravel Breeze + Filament v5
- [x] Desain database (30+ tabel) + migrasi + seeder
- [x] Storefront: home, katalog, detail produk, keranjang, checkout, pesanan
- [x] Admin panel CRUD: kategori, produk, bank, banner, kupon, pengeluaran, pemasok, PO
- [x] Dashboard admin (stats, revenue, stok)
- [x] SEO: meta tags, OG, sitemap XML, Schema.org
- [x] Wishlist, Guest Checkout, Midtrans Payment
- [x] Multi-role admin (Spatie Permission)
- [x] POS interface
- [x] Notifikasi email + WhatsApp (Fonnte)
- [x] Loyalty points + referral system
- [x] Ulasan produk (Reviews)
- [x] Halaman statis (Pages)
- [x] RajaOngkir API

### ✅ Phase 2 — Lanjutan
- [x] **Live Search Suggestion** — Alpine.js fetch + JSON endpoint
- [x] **Frequently Bought Together** — algoritma subquery order_items
- [x] **Dashboard Analytics** — Top Products, Slow Moving, Loyal Customers, AOV, Conversion Rate
- [x] **#1 Laporan Laba Rugi / Cash Flow** — filter bulan/tahun, CSV export
- [x] **#2 Refund Management** — workflow pending→approved→processed→completed
- [x] **#3 Audit Log Admin** — auto-log model events, read-only resource
- [x] **#4 Backup Database** — Artisan command + Filament page
- [x] **#5 Broadcast WhatsApp/Email** — pilih channel + recipient
- [x] **#7 Compare Products** — session-based (max 4), side-by-side
- [x] **#9 Chat / Live Chat** — customer ↔ admin, Alpine.js polling

### ⬜ Phase 3 — Rencana
- [ ] **#10 Restock Notification ("Notify Me")**
- [ ] **#11 Flash Sale Countdown Timer**
- [ ] **#12 Bundling / Paket Produk**
- [ ] **#13 Hold Order (POS)**
- [ ] **#14 Split Payment (POS)**
- [ ] **#15 Cash Drawer (POS)**
- [ ] **#16 Shift Kasir**
- [ ] **#17 Return via POS**
- [ ] **#18 Membership Tier (Silver/Gold/Platinum)**

---

## 📄 Lisensi

Hak cipta © 2026 — **ProCell Store**
