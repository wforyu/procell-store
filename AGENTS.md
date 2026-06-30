# AGENTS.md — ProCell Store

## Ringkasan Proyek

ProCell Store adalah toko *online* sparepart & aksesoris HP berbasis **Laravel 12** dengan admin panel **Filament v5**, *storefront* Blade, dan sistem auth **Laravel Breeze**. Target pasar Indonesia: 100% Bahasa Indonesia, kurir lokal (JNE, J&T, SiCepat, Ninja), pembayaran transfer bank (Mandiri, BCA, BRI) + **Midtrans** (Kartu Kredit, Virtual Account, Indomaret, Alfamart, QRIS, GoPay, ShopeePay, dll).

Dikembangkan secara bertahap mulai dari *scaffolding* Laravel Breeze, instalasi & konfigurasi Filament v5, desain database (20+ tabel), migrasi & seeder, *storefront* lengkap (home, katalog, detail produk, keranjang, *checkout* + guest checkout, pesanan, retur), admin panel (CRUD semua entitas, dashboard dengan grafik, pengaturan toko, **POS Interface**), SEO (sitemap XML, Schema.org, meta tags, Open Graph, JSON-LD), fitur *wishlist*, notifikasi email ke customer + notifikasi *database* ke admin, modul manajemen stok & pemasok (*purchase order*), **multi-role admin (Super Admin, Stok, Keuangan, Kasir)** via Spatie Permission, integrasi **Midtrans payment gateway**, serta **guest checkout** tanpa registrasi.

Proyek ini juga menyertakan berbagai *bug fix* dan *workaround* khusus untuk Filament v5 + PHP 8.2 yang terdokumentasi di bawah.

---

## Perintah Dasar

| Aksi | Perintah |
|------|----------|
| *Setup* pertama | `composer setup` |
| *Dev server* | `composer dev` (artisan serve + queue:listen + npm run dev) |
| Semua *test* | `composer test` (config:clear + artisan test) |
| *Test* spesifik | `php artisan test --filter=NamaTest` |
| *Lint* & perbaiki | `./vendor/bin/pint` |
| *Lint* cek saja | `./vendor/bin/pint --test` |
| *Build* aset | `npm run build` |
| *Cache* route | `php artisan route:cache` |
| *Cache* config | `php artisan config:cache` |
| Hapus *cache* config | `php artisan config:clear` |
| Hapus *cache* route | `php artisan route:clear` |
| Buat *storage link* | `php artisan storage:link` |

> **Catatan:** `php artisan optimize` gagal karena Filament v5 *issue* `view:cache`. Gunakan `route:cache` + `config:cache` terpisah.
> ⚠️ **PENTING:** Jangan `config:cache` sebelum `php artisan test`! *Cache* config akan *override* env `phpunit.xml` dan menyebabkan *test* `RefreshDatabase` nge-refresh **MySQL** (bukan SQLite). Selalu pakai `composer test` yang otomatis `config:clear` dulu, atau jalankan `config:clear` manual sebelum *test*.

---

## Arsitektur

### Stack Teknologi

| Komponen | Detail |
|----------|--------|
| **Framework** | Laravel 12 (PHP 8.2.12) — Blade templating, Eloquent ORM, Queue, Notification |
| **Admin Panel** | Filament v5.6.7 — CRUD, dashboard, widget, grafik, notifikasi database |
| **Auth Customer** | Laravel Breeze (Blade) — login, register, *forgot/reset password*, verifikasi email |
| **Multi-Role Admin** | Spatie Laravel Permission v6 — Super Admin, Stok, Keuangan, Kasir |
| **Payment Gateway** | Midtrans PHP SDK v2.6 — Snap (Kartu Kredit, VA, Convenience Store, QRIS, E-Wallet) |
| **Database** | MySQL via XAMPP (`procell_store`, root tanpa password, port 3306) |
| **Testing** | SQLite `:memory:` (otomatis di `phpunit.xml`) |
| **Frontend Build** | Vite + Tailwind CSS 4 |
| **Interaktivitas** | Alpine.js — carousel, accordion, keranjang, *password toggle*, *mega menu*, *coupon apply*, *wishlist toggle*, *banner popup*, konfirmasi modal, kalkulasi ongkir *realtime* |
| **Ikon** | Font Awesome 6 (Free via CDN) |
| **Asset** | Heroicons via Filament (admin panel) |

### Database — Semua Tabel

| Tabel | Fungsi |
|-------|--------|
| `users` | Admin & customer (`is_admin` boolean) |
| `customers` | Data tambahan customer (relasi 1:1 ke users, alamat, telepon) |
| `categories` | Kategori produk (`parent_id` untuk sub-kategori, `slug`, `is_active`) |
| `products` | Produk (stok, harga, *brand*, gambar utama, slug otomatis, SKU, *weight*, *is_active*) |
| `product_images` | Gambar tambahan produk (`is_primary` untuk *thumbnail*) |
| `carts` | Keranjang belanja (`user_id` atau `session_id`) |
| `cart_items` | Item dalam keranjang (`cart_id`, `product_id`, `quantity`) |
| `orders` | Pesanan — `order_number`, `user_id`, `status`, `total_amount`, `shipping_cost`, `courier`, `courier_service`, `tracking_number`, `payment_method`, `payment_proof`, `payment_verified_at`, `shipped_at`, `received_at`, `shipping_address`, `notes`, `coupon_id`, `discount_amount`, `midtrans_transaction_id`, `midtrans_payment_type` |
| `order_items` | Item dalam pesanan (`order_id`, `product_id`, `quantity`, `price`, `subtotal`) |
| `stock_movements` | Riwayat pergerakan stok (`type`: in/out, `quantity`, `note`, `user_id`, `product_id`) |
| `banners` | Banner slider halaman utama (*image*, *link*, *title*, *is_active*, *sort_order*) |
| `coupons` | Kupon diskon (*code*, *type*: percentage/fixed, *value*, *min_order*, *max_uses*, *used_count*, *expires_at*, *is_active*) |
| `expenses` | Catatan pengeluaran toko (*category*, *amount*, *description*, *date*) |
| `settings` | Pengaturan toko (*key*-*value* — nama toko, deskripsi, kontak, footer, WhatsApp, jam operasional, *flash sale text*, dll) |
| `bank_accounts` | Rekening bank untuk pembayaran transfer (nama bank, nomor, atas nama, *is_active*, *sort_order*) |
| `returns` | Pengajuan retur barang (`return_number`, `order_id`, `user_id`, `reason`, `description`, `status`: pending/approved/rejected, `admin_note`, `reviewed_at`) |
| `return_images` | Foto bukti retur |
| `suppliers` | Pemasok barang (nama, kontak, alamat, `is_active`) |
| `purchase_orders` | *Purchase order* ke supplier (nomor PO, `status`: pending/sent/received/cancelled, `total`, `user_id`, `supplier_id`) |
| `purchase_order_items` | Item dalam PO (`product_id`, `quantity`, `price`, `subtotal`) |
| `wishlists` | Produk favorit customer (`user_id`, `product_id`) — *unique constraint* |
| `notifications` | Notifikasi *database* untuk admin panel (dari `php artisan notifications:table`) |
| `sessions` | Session *database driver* |
| `cache` | *Cache database driver* |
| `jobs` | Queue *database driver* |

### Direktori Penting

```
C:\Users\pro021\procell-store\
├── AGENTS.md                          ← Panduan proyek ini
├── app/
│   ├── Console/Kernel.php             — Register jadwal command
│   ├── Exports/
│   │   └── OrdersExport.php           — Export CSV pesanan untuk admin
│   ├── Filament/
│   │   ├── Pages/
│   │   │   ├── Dashboard.php          — Dashboard admin (stats, revenue chart, stok chart)
│   │   │   └── ManageSettings.php     — Halaman pengaturan toko (nama, kontak, jam, flash sale, dll)
│   │   ├── Resources/
│   │   │   ├── BankAccounts/          — CRUD rekening bank
│   │   │   │   ├── Schemas/BankAccountForm.php
│   │   │   │   ├── Tables/BankAccountsTable.php
│   │   │   │   └── BankAccountResource.php
│   │   │   ├── Banners/               — CRUD banner promosi
│   │   │   │   ├── Schemas/BannerForm.php
│   │   │   │   ├── Tables/BannersTable.php
│   │   │   │   └── BannerResource.php
│   │   │   ├── Categories/            — CRUD kategori produk
│   │   │   │   ├── Schemas/CategoryForm.php
│   │   │   │   ├── Tables/CategoriesTable.php
│   │   │   │   └── CategoryResource.php
│   │   │   ├── Coupons/               — CRUD kupon diskon
│   │   │   │   ├── Schemas/CouponForm.php
│   │   │   │   ├── Tables/CouponsTable.php
│   │   │   │   └── CouponResource.php
│   │   │   ├── Customers/             — Read-only + ViewCustomer + OrdersRelationManager
│   │   │   │   ├── Schemas/CustomerForm.php
│   │   │   │   ├── Tables/CustomersTable.php
│   │   │   │   ├── Pages/ViewCustomer.php
│   │   │   │   └── CustomerResource.php
│   │   │   ├── Expenses/              — CRUD pengeluaran
│   │   │   │   ├── Schemas/ExpenseForm.php
│   │   │   │   ├── Tables/ExpensesTable.php
│   │   │   │   └── ExpenseResource.php
│   │   │   ├── Orders/                — CRUD pesanan (form/table/pages)
│   │   │   │   ├── Schemas/OrderForm.php
│   │   │   │   ├── Tables/OrdersTable.php
│   │   │   │   ├── Pages/ViewOrder.php
│   │   │   │   └── OrderResource.php
│   │   │   ├── Products/              — CRUD produk (slug otomatis, image upload, stok)
│   │   │   │   ├── Schemas/ProductForm.php
│   │   │   │   ├── Tables/ProductsTable.php
│   │   │   │   └── ProductResource.php
│   │   │   ├── PurchaseOrders/        — CRUD PO ke supplier
│   │   │   │   ├── Schemas/PurchaseOrderForm.php
│   │   │   │   ├── Tables/PurchaseOrdersTable.php
│   │   │   │   └── PurchaseOrderResource.php
│   │   │   ├── Returns/               — CRUD retur (setujui/tolak + lihat foto)
│   │   │   │   ├── Schemas/ReturnForm.php
│   │   │   │   ├── Tables/ReturnsTable.php
│   │   │   │   └── ReturnResource.php
│   │   │   ├── StockMovements/        — Read-only audit log stok
│   │   │   │   ├── Tables/StockMovementsTable.php
│   │   │   │   └── StockMovementResource.php
│   │   │   └── Suppliers/             — CRUD pemasok
│   │   │       ├── Schemas/SupplierForm.php
│   │   │       ├── Tables/SuppliersTable.php
│   │   │       └── SupplierResource.php
│   │   └── Widgets/
│   │       ├── StatsOverview.php      — Widget ringkasan statistik (total pesanan, pendapatan, dll)
│   │       ├── RevenueChart.php       — Grafik pendapatan 30 hari
│   │       └── StockMovementChart.php — Grafik pergerakan stok
│   ├── Http/Controllers/
│   │   ├── Admin/
│   │   │   ├── OrderExportController.php — Export CSV pesanan
│   │   │   └── PosController.php      — POS interface (search, add, update, remove, checkout, receipt)
│   │   └── Store/
│   │       ├── CartController.php     — Keranjang (guest via session, login via user_id)
│   │       ├── CheckoutController.php — Checkout + kurir + kupon + pembayaran + guest checkout
│   │       ├── HomeController.php     — Beranda (produk unggulan, kategori, banner)
│   │       ├── MidtransController.php — Midtrans finish + notification handler
│   │       ├── OrderController.php    — Daftar/detail pesanan, konfirmasi terima, upload bukti bayar, notifikasi
│   │       ├── ProductController.php  — Katalog + detail + cari produk
│   │       ├── ReturnController.php   — Pengajuan retur + upload foto (notifikasi admin)
│   │       └── WishlistController.php — Tambah/hapus wishlist + daftar wishlist
│   └── ── ProfileController.php       — Edit profil customer
│   ├── Livewire/
│   │   └── Store/                     — Komponen Livewire (jika ada)
│   ├── Models/
│   │   ├── BankAccount.php            — Rekening bank (scope aktif, urutan)
│   │   ├── Banner.php                 — Banner promosi
│   │   ├── Cart.php                   — Keranjang (getTotalAttribute)
│   │   ├── CartItem.php               — Item keranjang (getSubtotalAttribute, product)
│   │   ├── Category.php               — Kategori produk (parent, children, products, scope aktif)
│   │   ├── Coupon.php                 — Kupon diskon (isValid scope)
│   │   ├── Customer.php               — Pelanggan (total_spent, last_order_date, user)
│   │   ├── Expense.php                — Pengeluaran
│   │   ├── Order.php                  — Pesanan (grandTotal, returns, items, user, scopeByStatus)
│   │   ├── OrderItem.php              — Item pesanan (product)
│   │   ├── Product.php                — Produk (slug otomatis, stock scopes, imageUrl, category, images)
│   │   ├── ProductImage.php           — Gambar produk (is_primary)
│   │   ├── PurchaseOrder.php          — PO (items, supplier, user)
│   │   ├── PurchaseOrderItem.php      — Item PO (product)
│   │   ├── ReturnImage.php            — Foto bukti retur (imageUrl)
│   │   ├── Returns.php                — Pengajuan retur (images, order, user)
│   │   ├── Setting.php                — Pengaturan toko (getValue helper)
│   │   ├── StockMovement.php          — Riwayat stok (product, user, type)
│   │   ├── Supplier.php               — Pemasok (scope aktif)
│   │   ├── User.php                   — User + FilamentUser (canAccessPanel via isAdmin)
│   │   └── Wishlist.php               — Produk favorit (user, product)
│   ├── Notifications/
│   │   ├── OrderStatusChanged.php     — Notifikasi email ke customer saat status pesanan berubah
│   │   └── ReturnStatusChanged.php    — Notifikasi email ke customer saat retur disetujui/ditolak
│   ├── Services/
│   │   └── MidtransService.php        — Snap token, redirect URL, notification handler
│   └── Providers/
│       ├── AppServiceProvider.php     — View composer untuk cart count + footer pages
│       └── Filament/
│           └── AdminPanelProvider.php — Konfigurasi panel admin, grup navigasi, widget, halaman
├── bootstrap/
├── config/
│   ├── app.php
│   ├── database.php
│   ├── filament.php                   — Konfigurasi Filament (path, branding, dll)
│   └── ... (file konfigurasi lainnya)
├── database/
│   ├── factories/
│   │   └── ProductFactory.php         — Factory untuk testing produk
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 0001_01_01_000001_create_cache_table.php
│   │   ├── 0001_01_01_000002_create_jobs_table.php
│   │   ├── ... (total 19+ migration: products, orders, cart, returns, coupons, wishlists, notifications, suppliers, purchase_orders, dll)
│   ├── seeders/
│   │   ├── DatabaseSeeder.php         — Admin, customer, 8 kategori, setting default, 3 bank
│   │   ├── ProductSeeder.php          — 45 produk sample (6 brand, 8 kategori)
│   │   ├── BankAccountSeeder.php      — 3 rekening bank (Mandiri, BCA, BRI)
│   │   └── CouponSeeder.php           — 1 kupon demo "Pro-Diskon 30%"
│   └── sql/                           — Backup SQL (jika ada)
├── public/
│   └── storage/                       — Symlink ke storage/app/public
│       ├── payment-proofs/
│       ├── return-images/
│       └── products/
├── resources/views/
│   ├── layouts/
│   │   ├── app.blade.php              — Layout utama (SEO meta, top bar, header, mega menu, footer, wishlist link)
│   │   └── guest.blade.php            — Layout auth (split-screen: brand panel + form card)
│   ├── components/                    — Blade components (password-input, dll)
│   ├── auth/                          — 6 halaman auth redesigned (login, register, forgot, reset, verify, confirm)
│   ├── profile/                       — Edit profil customer + wishlist card
│   ├── store/                         — Halaman depan toko
│   │   ├── index.blade.php            — Beranda (banner slider, kategori unggulan, flash sale, produk terbaru)
│   │   ├── products/
│   │   │   ├── index.blade.php        — Katalog produk (grid, filter, search, pagination)
│   │   │   └── show.blade.php         — Detail produk (gambar, harga, spesifikasi, wishlist button, JSON-LD)
│   │   ├── cart/
│   │   │   └── index.blade.php        — Halaman keranjang (list item, quantity, total, coupon)
│   │   ├── checkout/
│   │   │   ├── index.blade.php        — Checkout (alamat, pilih kurir ongkir realtime, pilih bank/Midtrans, ringkasan)
│   │   │   └── success.blade.php      — Sukses (info bank/Midtrans, langkah selanjutnya, prompt register guest)
│   │   ├── orders/
│   │   │   ├── index.blade.php        — Daftar pesanan customer
│   │   │   └── show.blade.php         — Detail pesanan (upload bukti bayar, konfirmasi terima, retur, button wishlist)
│   │   ├── returns/
│   │   │   └── create.blade.php       — Form pengajuan retur (alasan, deskripsi, upload foto)
│   │   ├── wishlist/
│   │   │   └── index.blade.php        — Daftar wishlist customer
│   │   └── layouts/                   — Parsial
│   │       ├── header.blade.php       — Header navigasi + wishlist link
│   │       ├── footer.blade.php       — Footer (pages dinamis dari settings)
│   │       ├── mega-menu.blade.php    — Mega menu kategori
│   │       └── mobile-sidebar.blade.php — Sidebar mobile + wishlist link
│   ├── admin/
│   │   └── pos/
│   │       ├── index.blade.php          — POS interface (product grid, cart panel, checkout)
│   │       ├── _cart.blade.php          — POS partial: cart items list
│   │       ├── _products.blade.php      — POS partial: product cards grid
│   │       └── receipt.blade.php        — POS printable receipt
│   └── filament/
│       ├── orders/
│       │   └── payment-proof-modal.blade.php — Modal bukti transfer di admin
│       └── returns/
│           ├── images.blade.php        — Gallery foto retur
│           └── no-images.blade.php     — Fallback jika tidak ada foto
├── routes/
│   ├── web.php                        — Semua route storefront + auth + wishlist + sitemap
│   └── filament.php                    — Route khusus Filament (jika ada)
├── storage/
│   └── app/public/
│       ├── payment-proofs/
│       ├── return-images/
│       └── products/
├── tests/
│   ├── Feature/
│   │   ├── Store/
│   │   │   ├── CartTest.php
│   │   │   ├── CheckoutTest.php
│   │   │   ├── HomeTest.php
│   │   │   ├── OrderTest.php
│   │   │   ├── ProductTest.php
│   │   │   └── ReturnTest.php
│   │   └── ProfileTest.php
│   └── Unit/
│       └── ProductTest.php
├── .editorconfig
├── .env
├── .env.example
├── artisan
├── composer.json
├── package.json
├── phpunit.xml
├── tailwind.config.js
├── vite.config.js
└── ...
```

---

## Alur Aplikasi Lengkap

### 1. Toko (*Storefront*)

```
Home → Banner slider, kategori unggulan, flash sale, produk terbaru
         ├─ Link: detail kategori → katalog terfilter
         ├─ Link: detail produk
         └─ Link: "Lihat Semua" → katalog
Katalog → Grid produk + filter kategori + search (?q=)
Detail Produk → Gambar utama + gallery, harga, stok, brand, spesifikasi
                ├─ Tombol "Tambah ke Keranjang" → cart/checkout
                └─ Tombol Wishlist (love) → toggle wishlist
Keranjang → List item + quantity control + subtotal + kupon
            ├─ Validasi stok
            ├─ Apply kupon diskon
            └─ Tombol "Lanjut Checkout"
```

### 2. *Checkout* & Pesanan

```
Checkout (Auth):
1. Isi alamat pengiriman (jika belum lengkap)
2. Pilih KURIR: JNE (REG/YES/OKE), J&T (REG/YES), SiCepat (REG/BEST), Ninja (REG/2DAY)
   → Ongkir otomatis kalkulasi + total berubah REALTIME via Alpine.js + RajaOngkir API (fallback ke statis jika API tidak dikonfigurasi)
3. Pilih PEMBAYARAN:
   ├─ Transfer Bank → pilih bank tujuan (Mandiri/BCA/BRI)
   └─ Midtrans (Kartu Kredit, Virtual Account, Indomaret, Alfamart, QRIS, GoPay, ShopeePay)
4. Kupon diskon (opsional) → masukkan kode
5. Total = Subtotal + Ongkir - Diskon
6. Klik "Buat Pesanan" → stok berkurang + stock_movement tercatat
   → Jika Midtrans: redirect ke Snap payment page
   → Jika Transfer Bank: pending menunggu upload bukti bayar

Checkout (Guest):
1. Isi NAMA + EMAIL + TELEPON + alamat pengiriman
2-6. Sama seperti auth checkout
7. Order tersimpan di session `guest_orders`
8. Setelah daftar/login → order otomatis tertaut ke akun baru

Flow Status Pesanan:
  pending (Menunggu Pembayaran)
    ↓ customer upload bukti transfer (di detail pesanan)
  waiting_confirmation (Menunggu Konfirmasi Admin)
    ↓ admin klik "Konfirmasi Bayar" → email notif ke customer
  processing (Diproses)
    ↓ admin klik "Kirim" → input kurir + no resi → email notif ke customer
  shipped (Dikirim)
    ↓ customer klik "Pesanan Diterima"
  completed (Selesai)
    ↓
  cancelled (Dibatalkan) — bisa dari pending/waiting_confirmation/processing
    → jika pending: stok dikembalikan

### Aksi Customer di Detail Pesanan:
- Upload bukti transfer (saat pending)
- Konfirmasi pesanan diterima (saat shipped) → otomatis completed
- Ajukan retur (saat shipped/completed, maks 1x per pesanan)
- Lihat status terbaru + tracking number

### Notifikasi:
- Setiap perubahan status pesanan → email ke customer (via OrderStatusChanged notification)
- Customer upload bukti bayar → notifikasi database ke semua admin (Filament Notification)
- Customer ajukan retur → notifikasi database ke semua admin
- Admin setujui/tolak retur → notifikasi database ke customer (Filament Notification)
```

### 3. Retur Barang (Customer)

```
1. Buka detail pesanan → klik "Ajukan Retur"
2. Pilih alasan: Produk Cacat / Tidak Sesuai / Rusak Saat Kirim / Lainnya
3. Upload foto bukti (min 1, maks 5, format JPG/PNG, max 2MB per foto)
4. Kirim → status retur "pending" → admin dapat notifikasi database

Flow Status Retur:
  pending (Menunggu)
    ↓ admin setujui → notif database + email ke customer
  approved (Disetujui)
    ↓
  rejected (Ditolak) — admin wajib kasih alasan → notif database + email ke customer
```

### 4. Admin Panel (Filament v5)

```
Menu Navigasi:

Katalog:
  ├─ Kategori           → CRUD kategori (parent_id, slug otomatis, helper text)
  └─ Produk             → CRUD produk (gambar, stok, harga, brand, slug, SKU, helper text)

Persediaan:
  ├─ Stok               → Read-only audit log pergerakan stok
  ├─ Pemasok            → CRUD supplier (nama, kontak, alamat, helper text)
  └─ Purchase Order     → CRUD PO (pilih supplier, produk, quantity + price otomatis, total)
                           Status: pending → sent → received (tambah stok + stock_movement) → cancelled

Transaksi:
  ├─ POS                → POS interface (product grid, cart, customer, payment via cash/bank_transfer)
  │                       → Order number prefix `POS-`, status langsung `completed`
  │                       → Stok otomatis berkurang + stock_movement tercatat
  ├─ Pesanan            → Lihat/ubah status, filter (status), aksi per-item:
  │                       ├─ Konfirmasi Bayar (notif email customer)
  │                       ├─ Proses (notif email customer)
  │                       ├─ Kirim (input kurir + resi, notif email customer)
  │                       ├─ Selesai (notif email customer)
  │                       ├─ Batalkan (notif email customer)
  │                       └─ Edit
  │                       → Export CSV
  └─ Retur               → Lihat pengajuan + foto bukti
                           ├─ Setujui (notif database + email customer)
                           └─ Tolak + catatan wajib (notif database + email customer)

Konten:
  ├─ Banner              → CRUD banner slider (gambar, link, judul, helper text)
  └─ Pengeluaran         → CRUD catatan pengeluaran toko (kategori, jumlah, helper text)

Pelanggan:
  └─ Pelanggan           → Read-only daftar customer + detail + riwayat pesanan

Promo:
  └─ Kupon               → CRUD kupon diskon (kode, tipe, nilai, min order, helper text)

Pengaturan:
  ├─ Rekening Bank       → CRUD rekening (aktif/nonaktif, urutan, helper text)
  ├─ Dashboard           → Statistik, grafik pendapatan 30 hari, grafik pergerakan stok
  └─ Pengaturan Toko     → Nama toko, deskripsi, email, telepon, alamat, footer, WhatsApp,
                            jam operasional, toggle libur + pesan libur, flash sale text
```

### 5. SEO

```
- Dynamic <title> per halaman (via @section('title'))
- Meta description (via @section('meta_description'))
- Open Graph tags: og:title, og:description, og:image, og:type, og:url (via @section)
- Meta robots (index/follow default)
- Sitemap.xml otomatis di GET /sitemap.xml
- Schema.org Organization di layout head (JSON-LD via @php + json_encode)
- Schema.org BreadcrumbList di halaman produk
- Schema.org Product markup di halaman detail produk
- Catatan: SEMUA JSON-LD harus via @php + json_encode() — tidak boleh inline {} karena konflik Livewire
```

---

## Fitur Lengkap (A-Z)

| Fitur | Status | Keterangan |
|-------|--------|------------|
| Auth customer (Breeze) | ✅ Selesai | Login, register, forgot/reset password, email verify |
| Auth admin (Filament) | ✅ Selesai | canAccessPanel via isAdmin |
| Banner slider | ✅ Selesai | CRUD admin + tampil di home |
| Cart (guest + logged in) | ✅ Selesai | Session-based untuk guest, user_id untuk login |
| Checkout | ✅ Selesai | Alamat, kurir (4), ongkir realtime Alpine + RajaOngkir API, bank transfer, Midtrans, kupon |
| Guest checkout | ✅ Selesai | Checkout tanpa registrasi, order tertaut setelah daftar |
| RajaOngkir API | ✅ Selesai | Service class, AJAX endpoint, fallback statis jika API tidak dikonfigurasi |
| Coupon diskon | ✅ Selesai | Percentage/fixed, min order, max uses, expiry |
| Customer management | ✅ Selesai | Read-only admin, detail + order history |
| Dashboard admin | ✅ Selesai | StatsOverview, RevenueChart, StockMovementChart |
| Expense | ✅ Selesai | CRUD pengeluaran toko |
| Export CSV pesanan | ✅ Selesai | Dari admin panel |
| Footer pages dinamis | ✅ Selesai | View Composer di AppServiceProvider |
| Header settings | ✅ Selesai | Jam operasional, toggle libur, flash sale text |
| Helper text admin | ✅ Selesai | Semua form Filament ada helper text deskriptif |
| Kategori produk | ✅ Selesai | CRUD dengan parent_id untuk sub-kategori |
| Keranjang | ✅ Selesai | Qty control, subtotal, total, validasi stok |
| Kupon diskon | ✅ Selesai | Apply di checkout + admin CRUD |
| Migrasi database | ✅ Selesai | 20+ tabel lengkap dengan relasi |
| Notifikasi email customer | ✅ Selesai | OrderStatusChanged + ReturnStatusChanged (via queue) |
| Notifikasi email admin | ✅ Selesai | PaymentUploaded + ReturnSubmitted (via queue) |
| Notifikasi database admin | ✅ Selesai | Upload bayar, retur baru (Filament Notification) |
| Pesanan (admin) | ✅ Selesai | CRUD + actions + filter + export |
| Pesanan (customer) | ✅ Selesai | Daftar, detail, upload bayar, konfirmasi terima |
| Produk (admin) | ✅ Selesai | CRUD + gambar + stok + slug otomatis |
| Produk (storefront) | ✅ Selesai | Katalog, detail, search, filter kategori |
| Purchase Order | ✅ Selesai | PO ke supplier, received → auto tambah stok |
| Queue | ✅ Selesai | Database driver untuk notifikasi |
| Retur (admin) | ✅ Selesai | Setujui/tolak + lihat foto + notifikasi email ke customer + database ke admin |
| Retur (customer) | ✅ Selesai | Ajukan retur + upload foto + notifikasi admin |
| Seeder data sample | ✅ Selesai | 45 produk, 8 kategori, 6 brand, 3 bank, 1 kupon |
| Settings toko | ✅ Selesai | ManageSettings page (nama, kontak, jam, flash sale, dll) |
| SEO | ✅ Selesai | Meta tags, OG, sitemap XML, Schema.org JSON-LD |
| SMTP Konfigurasi | ✅ Selesai | Admin bisa setting email (host, port, username, password, enkripsi, dari) tanpa edit `.env` |
| Stock management | ✅ Selesai | Stock movement log, otomatis saat order/PO received |
| Supplier management | ✅ Selesai | CRUD pemasok |
| Guest checkout | ✅ Selesai | Checkout tanpa registrasi, order tertaut setelah daftar |
| Midtrans payment | ✅ Selesai | Snap redirect (Kartu Kredit, VA, Convenience Store, QRIS, E-Wallet) |
| Multi-role admin | ✅ Selesai | Super Admin, Stok, Keuangan, Kasir (Spatie Permission) |
| POS (Point of Sale) | ✅ Selesai | Interface kasir, stok auto-decrement, order completed langsung |
| Testing | ✅ Selesai | Feature + Unit tests via SQLite memory |
| Wishlist | ✅ Selesai | Toggle + daftar + link di topbar, dropdown, sidebar mobile |

---

## Konvensi Kode & *Workaround*

### Indentasi & Style
- Indentasi 4 spasi (`.editorconfig`)
- PSR-12 via Laravel Pint
- Label & konten 100% Bahasa Indonesia

### Filament v5 API
- Gunakan `Schema` (bukan `Form`) — namespace `Filament\Schemas\Components`
- Import `Section` dari `Filament\Schemas\Components\Section` (BUKAN `Filament\Forms\Components\Section`)
- Gunakan enum `Heroicon::Outlined*`
- `$view` dan `$heading` sebagai properti *non-static*

### PHP 8.2 — `UnitEnum` Type Hint Issue
- **MASALAH:** PHP 8.2 mewajibkan properti di *child class* punya tipe yang persis sama dengan *parent class*.
  Parent `Filament\Pages\Page` punya `protected static ?string $navigationGroup = null`.
  Tapi secara internal Filament v5 menggunakan `UnitEnum|string|null` untuk *type hint* di beberapa metode.
  Akibatnya: `protected static UnitEnum|string|null $navigationGroup = 'Pengaturan'` → `Fatal error: Type of ... must be UnitEnum|string|null (as in class Filament\Pages\Page)`.
- **SOLUSI:** JANGAN deklarasikan `$navigationGroup` sebagai properti di kelas *child*. Gunakan metode override:
  ```php
  public static function getNavigationGroup(): string
  {
      return 'Pengaturan';
  }
  ```
- **Tooltip navigasi:** Override `getNavigationItems()` → chain `->extraAttributes(['title' => '...'])`

### Livewire Blade — Konflik `{}`
- **MASALAH:** *Blade extension* dari Livewire menginterpretasikan karakter `{` literal di dalam `<script>` atau template sebagai *context syntax*, menyuntikkan blok PHP `if` yang tidak ditutup sehingga merusak halaman.
- **SOLUSI:** SEMUA JSON-LD harus dibangun dalam blok `@php` menggunakan `json_encode()`, bukan ditulis *inline* di Blade.
  ```php
  @php
      $schema = json_encode([
          '@context' => 'https://schema.org',
          '@type' => 'Organization',
          'name' => $storeName,
      ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
  @endphp
  <script type="application/ld+json">{!! $schema !!}</script>
  ```

### `@php` Blade Compilation
- **MASALAH:** Hanya blok `@php ... @endphp` PERTAMA di `layouts/app.blade.php` yang dikompilasi dengan benar. Blok `@php` kedua tetap sebagai teks literal `@php` (tidak dieksekusi).
- **SOLUSI:** Letakkan SEMUA inisialisasi variabel PHP dalam satu blok `@php` di bagian atas file.

### Dual Rendering Layout
- `layouts/app.blade.php` mendukung dua mode:
  - `{{ $slot }}` — untuk komponen Breeze (profile, auth pages)
  - `@yield('content')` — untuk *storefront* yang menggunakan `@extends('layouts.app')`

### Cart & Wishlist View Composer
- Jumlah item keranjang + status wishlist disediakan via `View::composer` di `AppServiceProvider`
- Tidak perlu query manual di setiap Blade template

### Notifikasi
- Customer: hanya `mail` *channel* — tidak punya Filament bell
- Admin: `Filament\Notifications\Notification::make()->sendToDatabase()` — database-only
- Admin juga dapet email via `PaymentUploaded` & `ReturnSubmitted`
- Semua notifikasi implement `ShouldQueue` & `Queueable` untuk performa

---

## Akun Default (*Seeder*)

| Role | Email | Password |
|------|-------|----------|
| Admin | `admin@procell.com` | `admin123` |
| Customer | `customer@procell.com` | `customer123` |

## Data Sample (*Seeder*)

- **8 Kategori**: LCD & Display, Battery, Flexible Cable, Mainboard & IC, Button & Switch, Charger & Adapter, Data Cable, Accessories
- **45 Produk** tersebar di 8 kategori, 6 brand (Samsung, iPhone, Xiaomi, OPPO, Vivo, Realme)
- **3 Bank**: Mandiri (1234567890), BCA (0987654321), BRI (5556667777)
- **12+ Settings**: Nama toko (ProCell Store), deskripsi, email, telepon, alamat, footer, WhatsApp, jam operasional, *flash sale text*, dll
- **1 Kupon Demo**: Kode `Pro-Diskon 30%` — diskon 30%, minimal belanja Rp0, berlaku 1 tahun

---

## *Bug Fixes* & *Workarounds* Tercatat

| Issue | Solusi |
|-------|--------|
| `Class "Filament\Forms\Components\Section" not found` | Ganti import ke `Filament\Schemas\Components\Section` |
| `Undefined variable $footerPages` | Pindahkan inisialisasi ke `View::composer` di `AppServiceProvider` |
| `$navigationGroup` fatal error PHP 8.2 | Hapus properti, gunakan `getNavigationGroup(): string` |
| Duplicate `@php` block tidak dikompilasi | Gabung semua inisialisasi ke satu blok `@php` |
| JSON-LD `{}` konflik Livewire Blade | Gunakan `json_encode()` dalam `@php`, jangan inline |
| `config:cache` override env phpunit.xml | Selalu `config:clear` sebelum test (via `composer test`) |
| `php artisan optimize` gagal | Gunakan `route:cache` + `config:cache` terpisah |
| Kupon "Pro-Diskon 30%" expired | Update `expires_at` +1 tahun, `min_order` 0 via CouponSeeder |
| `@alpinejs/mask` tidak terinstal | `x-mask:dynamic` pakai `RawJs` dengan *single quotes* di JS |

---

## Catatan Penting

- Semua *test* pakai SQLite `:memory:` (bukan MySQL) — aman dijalankan kapan saja, `composer test` otomatis `config:clear`
- `composer dev` tidak termasuk `pail` (pcntl tidak ada di Windows)
- *Storage link* sudah dibuat (`public/storage`)
- Direktori *upload*: `payment-proofs/`, `return-images/`, `products/`
- Status enum `orders.status` sudah mencakup `waiting_confirmation`
- Retur maksimal 1 pengajuan aktif per pesanan (pending/approved belum bisa ajukan lagi)
- Total harga pesanan = `total_amount` (produk) + `shipping_cost` (ongkir) via `$order->grand_total`
- `APP_URL=http://localhost:8000`
- `FILESYSTEM_DISK=public`
- `MAIL_MAILER=log` di `.env` sebagai default. Admin bisa konfigurasi SMTP via Pengaturan Toko → Konfigurasi Email (SMTP) tanpa perlu edit `.env`
- `QUEUE_CONNECTION=database` — notifikasi diproses via queue
- RajaOngkir: admin bisa setting API Key + ID kota asal di Pengaturan Toko → RajaOngkir. Jika tidak dikonfigurasi, ongkir menggunakan tarif statis (fallback)
- Midtrans: admin setting Server Key + Client Key + mode production di Pengaturan Toko → Midtrans. Callback URL: `/midtrans/notification` (POST) dan `/midtrans/finish/{order}` (GET)
- POS: tersedia di `/admin/pos`, hanya untuk role Kasir + Super Admin + Stok + Keuangan. Kasir tidak punya akses ke Filament panel, langsung redirect ke POS
- Guest checkout: order disimpan di session `guest_orders`. Setelah registrasi, order otomatis tertaut ke akun baru berdasarkan email

---

## *Roadmap* / Selanjutnya

Prioritas tinggi:
1. ~~**Konfigurasi SMTP di ManageSettings**~~ ✅ Selesai — Admin bisa setting email langsung dari panel tanpa edit `.env`
2. ~~**Integrasi RajaOngkir / Binderbyte**~~ ✅ Selesai — Hitung ongkir real-time via RajaOngkir API (fallback ke statis jika API tidak dikonfigurasi)

Prioritas menengah:
3. ~~**Multi-admin & roles**~~ ✅ Selesai — Spatie Laravel Permission untuk membedakan akses admin (super admin, stok, keuangan, kasir)
4. **Loyalty points + referral system** — Poin belanja + kode referral
5. ~~**Guest checkout**~~ ✅ Selesai — Checkout tanpa registrasi

Prioritas rendah:
6. ~~**POS (*Point of Sale*) interface**~~ ✅ Selesai — Antarmuka kasir untuk toko offline
7. ~~**Integrasi payment gateway**~~ ✅ Selesai — Midtrans Snap (Kartu Kredit, VA, Convenience Store, QRIS, E-Wallet)
8. **Notifikasi WhatsApp** — Menggunakan API Fonnte / Wablas untuk konfirmasi pesanan via WA

---

## Catatan Git & Deployment

Untuk push ke GitHub:
1. `git init`
2. `git add .`
3. `git commit -m "Initial commit: ProCell Store Laravel 12 + Filament v5"`
4. Buat repo di GitHub, lalu:
   ```
   git remote add origin https://github.com/username/procell-store.git
   git branch -M main
   git push -u origin main
   ```
5. Atau jika sudah terhubung ke remote:
   ```
   git add -A
   git commit -m "Pesan commit"
   git push
   ```
