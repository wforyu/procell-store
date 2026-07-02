# AGENTS.md — ProCell Store

## Ringkasan Proyek

ProCell Store adalah toko *online* sparepart & aksesoris HP berbasis **Laravel 12** dengan admin panel **Filament v5**, *storefront* Blade, dan sistem auth **Laravel Breeze**. Target pasar Indonesia: 100% Bahasa Indonesia, kurir lokal (JNE, J&T, SiCepat, Ninja), pembayaran transfer bank (Mandiri, BCA, BRI) + **Midtrans** (Kartu Kredit, Virtual Account, Indomaret, Alfamart, QRIS, GoPay, ShopeePay, dll).

Dikembangkan secara bertahap mulai dari *scaffolding* Laravel Breeze, instalasi & konfigurasi Filament v5, desain database (20+ tabel), migrasi & seeder, *storefront* lengkap (home, katalog, detail produk, keranjang, *checkout* + guest checkout, pesanan, retur, compare, chat), admin panel (CRUD semua entitas, dashboard dengan grafik & analitik, pengaturan toko, **POS Interface**), SEO (sitemap XML, Schema.org, meta tags, Open Graph, JSON-LD), fitur *wishlist*, notifikasi email ke customer + notifikasi *database* ke admin, modul manajemen stok & pemasok (*purchase order*), **multi-role admin (Super Admin, Stok, Keuangan, Kasir)** via Spatie Permission, integrasi **Midtrans payment gateway**, **guest checkout**, **Laporan Laba Rugi**, **Refund Management**, **Audit Log Admin**, **Backup Database**, **Broadcast WhatsApp/Email**, **Compare Products**, **Chat / Live Chat**, **Live Search Suggestion**, **Frequently Bought Together**, **Dashboard Analytics** (Top Products, Slow Moving, Loyal Customers, AOV, Conversion Rate, Profit), serta **loyalty points + referral system**.

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
| *Seed* ulang data sample | `php artisan db:seed` |
| *Backup* database | `php artisan db:backup` |

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
| **Interaktivitas** | Alpine.js — carousel, accordion, keranjang, *password toggle*, *mega menu*, *coupon apply*, *wishlist toggle*, *banner popup*, konfirmasi modal, kalkulasi ongkir *realtime*, *live search*, *chat polling* |
| **Ikon** | Font Awesome 6 (Free via CDN) |
| **Asset** | Heroicons via Filament (admin panel) |

### Database — Semua Tabel

| Tabel | Fungsi |
|-------|--------|
| `users` | Admin & customer (`is_admin` boolean, Spatie roles) |
| `customers` | Data tambahan customer (relasi 1:1 ke users, alamat, telepon) |
| `categories` | Kategori produk (`parent_id` untuk sub-kategori, `slug`, `is_active`) |
| `products` | Produk (stok, harga, *brand*, gambar utama, slug otomatis, SKU, *weight*, *is_active*, promo_price, review_stats) |
| `product_images` | Gambar tambahan produk (`is_primary` untuk *thumbnail*) |
| `product_reviews` | Ulasan produk dari customer (rating, komentar, order_id) |
| `carts` | Keranjang belanja (`user_id` atau `session_id`) |
| `cart_items` | Item dalam keranjang (`cart_id`, `product_id`, `quantity`) |
| `orders` | Pesanan — `order_number`, `user_id`, `status`, `total_amount`, `shipping_cost`, `courier`, `courier_service`, `tracking_number`, `payment_method`, `payment_proof`, `payment_verified_at`, `shipped_at`, `received_at`, `shipping_address`, `notes`, `coupon_id`, `discount_amount`, `points_used`, `points_discount`, `points_earned`, `midtrans_transaction_id`, `midtrans_payment_type` |
| `order_items` | Item dalam pesanan (`order_id`, `product_id`, `quantity`, `price`, `subtotal`) |
| `stock_movements` | Riwayat pergerakan stok (`type`: in/out, `quantity`, `note`, `user_id`, `product_id`) |
| `banners` | Banner slider halaman utama (*image*, *link*, *title*, *is_active*, *sort_order*, *type*) |
| `coupons` | Kupon diskon (*code*, *type*: percentage/fixed, *value*, *min_order*, *max_uses*, *used_count*, *expires_at*, *is_active*) |
| `coupon_usages` | Riwayat pemakaian kupon per user |
| `expenses` | Catatan pengeluaran toko (*category*, *amount*, *description*, *date*) |
| `settings` | Pengaturan toko (*key*-*value* — nama toko, deskripsi, kontak, footer, WhatsApp, jam operasional, *flash sale text*, midtrans, rajaongkir, fonnte, loyalty, dll) |
| `bank_accounts` | Rekening bank untuk pembayaran transfer (nama bank, nomor, atas nama, *is_active*, *sort_order*) |
| `returns` | Pengajuan retur barang (`return_number`, `order_id`, `user_id`, `reason`, `description`, `status`: pending/approved/rejected, `admin_note`, `reviewed_at`) |
| `return_images` | Foto bukti retur |
| `suppliers` | Pemasok barang (nama, kontak, alamat, `is_active`) |
| `purchase_orders` | *Purchase order* ke supplier (nomor PO, `status`: draft/ordered/partially_received/received/cancelled, `total_amount`, `user_id`, `supplier_id`) |
| `purchase_order_items` | Item dalam PO (`product_id`, `quantity`, `price`, `subtotal`) |
| `wishlists` | Produk favorit customer (`user_id`, `product_id`) — *unique constraint* |
| `pages` | Halaman statis (tentang kami, kebijakan privasi, dll) — `title`, `slug`, `content`, `is_active` |
| `notifications` | Notifikasi *database* untuk admin panel (dari `php artisan notifications:table`) |
| `sessions` | Session *database driver* |
| `imports` | Riwayat impor data |
| `exports` | Riwayat ekspor data |
| `failed_import_rows` | Baris gagal impor |
| `permissions` | Spatie Permission |
| `roles` | Spatie Role |
| `model_has_roles` | Spatie Pivot Role-User |
| `model_has_permissions` | Spatie Pivot Permission-User |
| `role_has_permissions` | Spatie Pivot Role-Permission |
| `loyalty_points` | Poin loyalitas per user (`user_id`, `balance`) |
| `loyalty_point_transactions` | Riwayat transaksi poin (earn/redeem/expire, amount, description, reference_type, reference_id) |
| `referral_codes` | Kode referral user (`user_id`, `code`, `bonus_points`) |
| `chat_conversations` | Percakapan chat (`user_id`, `subject`, `status`: open/closed) |
| `chat_messages` | Isi pesan chat (`conversation_id`, `user_id`, `message`, `is_admin`) |
| `refunds` | Refund management (terpisah dari retur) — `refund_number`, `order_id`, `user_id`, `amount`, `reason`, `method`, `status`: pending/approved/processed/completed/rejected, `processed_by` |
| `admin_activity_logs` | Audit log aktivitas admin (`user_id`, `action`, `model_type`, `model_id`, `old_values`, `new_values`, `ip_address`, `user_agent`) |

### Direktori Penting

```
C:\Users\pro021\procell-store\
├── AGENTS.md                          ← Panduan proyek ini
├── README.md                          ← Dokumentasi proyek
├── app/
│   ├── Console/
│   │   ├── Kernel.php                 — Register jadwal command
│   │   └── Commands/
│   │       └── DatabaseBackup.php     — Artisan command `db:backup`
│   ├── Exports/
│   │   └── OrdersExport.php           — Export CSV pesanan untuk admin
│   ├── Filament/
│   │   ├── Pages/
│   │   │   ├── Dashboard.php          — Dashboard admin (stats, revenue chart, stok chart, top products, slow moving, loyal customers)
│   │   │   ├── ManageSettings.php     — Pengaturan toko (nama, kontak, jam, flash sale, SMTP, RajaOngkir, Midtrans, Fonnte, Loyalty)
│   │   │   ├── ProfitLossReport.php   — Laporan Laba Rugi & Arus Kas (filter bulan/tahun, CSV export)
│   │   │   ├── DatabaseBackupPage.php — Backup & restore database (download file SQL)
│   │   │   └── BroadcastPage.php      — Broadcast WhatsApp/Email ke customer
│   │   ├── Resources/
│   │   │   ├── AdminActivityLogs/     — Read-only audit log admin
│   │   │   │   ├── Tables/AdminActivityLogsTable.php
│   │   │   │   └── AdminActivityLogResource.php
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
│   │   │   ├── ChatConversations/     — Chat dengan pelanggan (lihat percakapan + balas)
│   │   │   │   ├── Schemas/ChatConversationForm.php
│   │   │   │   ├── Tables/ChatConversationsTable.php
│   │   │   │   ├── Pages/ListChatConversations.php
│   │   │   │   ├── Pages/EditChatConversation.php
│   │   │   │   └── ChatConversationResource.php
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
│   │   │   ├── Pages/                — CRUD halaman statis
│   │   │   │   ├── Schemas/PageForm.php
│   │   │   │   ├── Tables/PagesTable.php
│   │   │   │   ├── Pages/ListPages.php
│   │   │   │   ├── Pages/EditPage.php
│   │   │   │   ├── Pages/CreatePage.php
│   │   │   │   └── PageResource.php
│   │   │   ├── Products/              — CRUD produk (slug otomatis, image upload, stok)
│   │   │   │   ├── Schemas/ProductForm.php
│   │   │   │   ├── Tables/ProductsTable.php
│   │   │   │   └── ProductResource.php
│   │   │   ├── ProductReviews/        — CRUD ulasan produk
│   │   │   │   ├── Schemas/ProductReviewForm.php
│   │   │   │   ├── Tables/ProductReviewsTable.php
│   │   │   │   └── ProductReviewResource.php
│   │   │   ├── PurchaseOrders/        — CRUD PO ke supplier
│   │   │   │   ├── Schemas/PurchaseOrderForm.php
│   │   │   │   ├── Tables/PurchaseOrdersTable.php
│   │   │   │   └── PurchaseOrderResource.php
│   │   │   ├── Refunds/               — CRUD refund management
│   │   │   │   ├── Schemas/RefundForm.php
│   │   │   │   ├── Tables/RefundsTable.php
│   │   │   │   ├── Pages/ListRefunds.php
│   │   │   │   ├── Pages/EditRefund.php
│   │   │   │   ├── Pages/CreateRefund.php
│   │   │   │   └── RefundResource.php
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
│   │   ├── Resources/Sistem/          — Manajemen user & role (Spatie)
│   │   │   ├── Pages/CreateRole.php
│   │   │   ├── Pages/CreateUser.php
│   │   │   ├── Pages/EditRole.php
│   │   │   ├── Pages/EditUser.php
│   │   │   ├── Pages/ListRoles.php
│   │   │   └── Pages/ListUsers.php
│   │   └── Widgets/
│   │       ├── StatsOverviewWidget.php      — 7 stat cards: Revenue, Expenses, Gross Profit, AOV, Conversion Rate, Products, Low Stock
│   │       ├── RevenueChartWidget.php       — Grafik pendapatan 30 hari
│   │       ├── StockMovementChartWidget.php — Grafik pergerakan stok
│   │       ├── TopProductsTableWidget.php   — Top 10 produk terlaris
│   │       ├── SlowMovingProductsTableWidget.php — Produk stok tinggi penjualan rendah
│   │       └── LoyalCustomersTableWidget.php — Top 10 pelanggan setia
│   ├── Http/Controllers/
│   │   ├── Admin/
│   │   │   ├── ExportController.php    — Export CSV (orders, products, suppliers, laba rugi) + download backup
│   │   │   └── PosController.php       — POS interface (+ clearCart, skuAdd, customerAdd, history)
│   │   └── Store/
│   │       ├── CartController.php      — Keranjang (guest via session, login via user_id)
│   │       ├── ChatController.php      — Chat live dengan admin (Alpine.js polling 5 detik)
│   │       ├── CheckoutController.php  — Checkout + kurir + kupon + pembayaran + guest checkout
│   │       ├── CompareController.php   — Bandingkan produk (session-based, max 4)
│   │       ├── CouponController.php    — Apply/remove kupon diskon
│   │       ├── HomeController.php      — Beranda (produk unggulan, kategori, banner)
│   │       ├── MidtransController.php  — Midtrans finish + notification handler
│   │       ├── OrderController.php     — Daftar/detail pesanan, konfirmasi terima, upload bukti bayar, notifikasi
│   │       ├── PageController.php      — Halaman statis (tentang, kebijakan, dll)
│   │       ├── ProductController.php   — Katalog + detail + search + live suggestions + frequently bought together
│   │       ├── ReturnController.php    — Pengajuan retur + upload foto (notifikasi admin)
│   │       ├── ReviewController.php    — Ulasan produk setelah pesanan selesai
│   │       └── WishlistController.php  — Tambah/hapus wishlist + daftar wishlist
│   │   └── ProfileController.php       — Edit profil customer
│   ├── Livewire/
│   │   └── Store/                      — Komponen Livewire (jika ada)
│   ├── Models/
│   │   ├── AdminActivityLog.php        — Audit log admin
│   │   ├── BankAccount.php             — Rekening bank (scope aktif, urutan)
│   │   ├── Banner.php                  — Banner promosi
│   │   ├── Cart.php                    — Keranjang (getTotalAttribute)
│   │   ├── CartItem.php                — Item keranjang (getSubtotalAttribute, product)
│   │   ├── Category.php                — Kategori produk (parent, children, products, scope aktif)
│   │   ├── ChatConversation.php        — Percakapan chat (user, messages, latestMessage, scope open/forUser)
│   │   ├── ChatMessage.php             — Pesan chat (conversation, user)
│   │   ├── Coupon.php                  — Kupon diskon (isValid scope)
│   │   ├── CouponUsage.php             — Riwayat pemakaian kupon
│   │   ├── Customer.php                — Pelanggan (total_spent, last_order_date, user)
│   │   ├── Expense.php                 — Pengeluaran
│   │   ├── LoyaltyPoint.php            — Poin loyalitas (user, balance)
│   │   ├── LoyaltyPointTransaction.php — Riwayat poin (type: earn/redeem/expire)
│   │   ├── Order.php                   — Pesanan (grandTotal, returns, items, user, scopeByStatus)
│   │   ├── OrderItem.php               — Item pesanan (product)
│   │   ├── Page.php                    — Halaman statis (active scope)
│   │   ├── Product.php                 — Produk (slug otomatis, stock scopes, imageUrl, category, images, reviews)
│   │   ├── ProductImage.php            — Gambar produk (is_primary)
│   │   ├── ProductReview.php           — Ulasan produk (rating, komentar)
│   │   ├── PurchaseOrder.php           — PO (items, supplier, user)
│   │   ├── PurchaseOrderItem.php       — Item PO (product)
│   │   ├── ReferralCode.php            — Kode referral
│   │   ├── Refund.php                  — Refund management (auto-numbering RF-YYYYMM-XXXX)
│   │   ├── ReturnImage.php             — Foto bukti retur (imageUrl)
│   │   ├── Returns.php                 — Pengajuan retur (images, order, user)
│   │   ├── Setting.php                 — Pengaturan toko (getValue helper)
│   │   ├── StockMovement.php           — Riwayat stok (product, user, type)
│   │   ├── Supplier.php                — Pemasok (scope aktif)
│   │   ├── User.php                    — User + FilamentUser (canAccessPanel via isAdmin)
│   │   └── Wishlist.php                — Produk favorit (user, product)
│   ├── Notifications/
│   │   ├── BroadcastNotification.php   — Broadcast email/WhatsApp ke customer
│   │   ├── NewChatMessageAdmin.php     — Notifikasi database ke admin saat customer chat
│   │   ├── OrderStatusChanged.php      — Notifikasi email ke customer saat status pesanan berubah
│   │   ├── PaymentUploaded.php         — Notifikasi database ke admin saat customer upload bukti bayar
│   │   ├── ReturnStatusChanged.php     — Notifikasi email ke customer saat retur disetujui/ditolak
│   │   └── ReturnSubmitted.php         — Notifikasi database ke admin saat customer ajukan retur
│   ├── Observers/
│   │   └── OrderObserver.php           — Catat stock_movement saat order dibuat/dibatalkan
│   ├── Services/
│   │   ├── AdminActivityLogger.php     — Static helper log aktivitas admin (created/updated/deleted)
│   │   ├── FonnteService.php           — WhatsApp notification via Fonnte API
│   │   ├── LoyaltyService.php          — Poin loyalitas (earn, redeem, bonus referral)
│   │   └── MidtransService.php         — Snap token, redirect URL, notification handler
│   └── Providers/
│       ├── AppServiceProvider.php      — View composer (cart count, compare count, footer pages) + auto-log admin activity
│       └── Filament/
│           └── AdminPanelProvider.php  — Konfigurasi panel admin, grup navigasi, widget, halaman
├── bootstrap/
├── config/
│   ├── app.php
│   ├── database.php
│   ├── filament.php                    — Konfigurasi Filament (path, branding, dll)
│   └── ... (file konfigurasi lainnya)
├── database/
│   ├── factories/
│   │   └── ProductFactory.php          — Factory untuk testing produk
│   ├── migrations/
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 0001_01_01_000001_create_cache_table.php
│   │   ├── 0001_01_01_000002_create_jobs_table.php
│   │   ├── ... (total 47 migration: users, products, orders, cart, returns, coupons, wishlists, notifications, suppliers, purchase_orders, loyalty, chat, refunds, audit log, permissions, dll)
│   ├── seeders/
│   │   ├── DatabaseSeeder.php          — Admin, customer, 8 kategori, setting default
│   │   ├── ProductSeeder.php           — 45 produk sample (6 brand, 8 kategori)
│   │   ├── BankAccountSeeder.php       — 3 rekening bank (Mandiri, BCA, BRI)
│   │   ├── CouponSeeder.php            — 1 kupon demo "Pro-Diskon 30%"
│   │   ├── PageSeeder.php              — Halaman statis default
│   │   └── RoleSeeder.php              — Roles & permissions Spatie
│   └── sql/                            — Backup SQL (jika ada)
├── public/
│   └── storage/                        — Symlink ke storage/app/public
│       ├── payment-proofs/
│       ├── return-images/
│       └── products/
├── resources/views/
│   ├── layouts/
│   │   ├── app.blade.php               — Layout utama (SEO meta, top bar, header, mega menu, footer, wishlist link, compare badge, chat link)
│   │   └── guest.blade.php             — Layout auth (split-screen: brand panel + form card)
│   ├── components/                     — Blade components (password-input, dll)
│   ├── auth/                           — 6 halaman auth redesigned (login, register, forgot, reset, verify, confirm)
│   ├── profile/                        — Edit profil customer + wishlist card
│   ├── store/                          — Halaman depan toko
│   │   ├── index.blade.php             — Beranda (banner slider, kategori unggulan, flash sale, produk terbaru)
│   │   ├── chat/
│   │   │   ├── index.blade.php         — Daftar percakapan chat + form chat baru
│   │   │   └── show.blade.php          — Detail chat real-time (Alpine.js polling)
│   │   ├── compare/
│   │   │   └── index.blade.php         — Tabel perbandingan produk side-by-side
│   │   ├── products/
│   │   │   ├── index.blade.php         — Katalog produk (grid, filter, search, pagination, compare button)
│   │   │   └── show.blade.php          — Detail produk (gambar, harga, spesifikasi, wishlist, compare, frequently bought together, JSON-LD)
│   │   ├── cart/
│   │   │   └── index.blade.php         — Halaman keranjang (list item, quantity, total, coupon)
│   │   ├── checkout/
│   │   │   ├── index.blade.php         — Checkout (alamat, pilih kurir ongkir realtime, pilih bank/Midtrans, ringkasan)
│   │   │   └── success.blade.php       — Sukses (info bank/Midtrans, langkah selanjutnya, prompt register guest)
│   │   ├── orders/
│   │   │   ├── index.blade.php         — Daftar pesanan customer
│   │   │   └── show.blade.php          — Detail pesanan (upload bukti bayar, konfirmasi terima, retur, review, wishlist)
│   │   ├── returns/
│   │   │   └── create.blade.php        — Form pengajuan retur (alasan, deskripsi, upload foto)
│   │   ├── wishlist/
│   │   │   └── index.blade.php         — Daftar wishlist customer
│   │   └── layouts/                    — Parsial
│   │       ├── header.blade.php        — Header navigasi + wishlist link
│   │       ├── footer.blade.php        — Footer (pages dinamis dari settings)
│   │       ├── mega-menu.blade.php     — Mega menu kategori
│   │       └── mobile-sidebar.blade.php — Sidebar mobile + wishlist link
│   ├── admin/
│   │   └── pos/
│   │       ├── index.blade.php          — POS interface (product grid, cart panel, checkout)
│   │       ├── _cart.blade.php          — POS partial: cart items list
│   │       ├── _products.blade.php      — POS partial: product cards grid
│   │       ├── _history.blade.php       — POS partial: riwayat transaksi hari ini
│   │       └── receipt.blade.php        — POS printable receipt
│   └── filament/
│       ├── chat-conversations/
│       │   └── messages.blade.php      — View percakapan chat di admin
│       ├── orders/
│       │   └── payment-proof-modal.blade.php — Modal bukti transfer di admin
│       └── returns/
│           ├── images.blade.php        — Gallery foto retur
│           └── no-images.blade.php     — Fallback jika tidak ada foto
├── routes/
│   ├── web.php                         — Semua route storefront + auth + wishlist + compare + chat + sitemap
│   ├── auth.php                        — Route auth Breeze
│   └── filament.php                    — Route khusus Filament (jika ada)
├── storage/
│   └── app/public/
│       ├── backups/                    — Hasil backup database (.sql)
│       ├── payment-proofs/
│       ├── return-images/
│       └── products/
├── tests/
│   ├── Feature/
│   │   ├── Auth/                       — 7 test auth Breeze (login, register, password, email verification)
│   │   ├── ExampleTest.php
│   │   └── ProfileTest.php             — 5 test profil (edit, delete, email verification)
│   └── Unit/
│       └── ExampleTest.php
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
Katalog → Grid produk + filter kategori + search (?q=) + tombol compare
Detail Produk → Gambar utama + gallery, harga, stok, brand, spesifikasi, ulasan rating
                ├─ Tombol "Tambah ke Keranjang" → cart/checkout
                ├─ Tombol Wishlist (love) → toggle wishlist
                ├─ Tombol "Bandingkan" → toggle compare (max 4)
                └─ Frequently Bought Together → produk yang sering dibeli bersamaan
Keranjang → List item + quantity control + subtotal + kupon
            ├─ Validasi stok
            ├─ Apply kupon diskon
            └─ Tombol "Lanjut Checkout"
Live Search → Input di header → dropdown suggestions real-time (product name, brand, price, image)
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
5. Poin loyalitas (opsional) → redeem jika cukup
6. Total = Subtotal + Ongkir - Diskon - Poin
7. Klik "Buat Pesanan" → stok berkurang + stock_movement tercatat + poin earned
   → Jika Midtrans: redirect ke Snap payment page
   → Jika Transfer Bank: pending menunggu upload bukti bayar

Checkout (Guest):
1. Isi NAMA + EMAIL + TELEPON + alamat pengiriman
2-7. Sama seperti auth checkout
8. Order tersimpan di session `guest_orders`
9. Setelah daftar/login → order otomatis tertaut ke akun baru

Flow Status Pesanan:
  pending (Menunggu Pembayaran)
    ↓ customer upload bukti transfer (di detail pesanan)
  waiting_confirmation (Menunggu Konfirmasi Admin)
    ↓ admin klik "Konfirmasi Bayar" → email + WA notif ke customer
  processing (Diproses)
    ↓ admin klik "Kirim" → input kurir + no resi → email + WA notif ke customer
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
- Beri ulasan & rating (setelah completed)
- Lihat status terbaru + tracking number

### Notifikasi:
- Setiap perubahan status pesanan → email + WA ke customer
- Customer upload bukti bayar → notifikasi database + WA ke semua admin
- Customer ajukan retur → notifikasi database + WA ke semua admin
- Admin setujui/tolak retur → notifikasi database + WA ke customer
```

### 3. Chat / Live Chat

```
Customer:
1. Klik menu "Chat" di topbar / dropdown user / mobile sidebar
2. Lihat daftar percakapan → klik untuk buka
3. Kirim pesan baru → admin dapat notifikasi database
4. Pesan baru dari admin muncul real-time via Alpine.js polling (5 detik)

Admin (Filament "Layanan Pelanggan → Chat"):
1. Lihat daftar percakapan (status Aktif/Ditutup)
2. Klik percakapan → lihat riwayat chat
3. Tulis balasan + klik "Simpan & Balas"
4. Tutup percakapan jika selesai
```

### 4. Compare Products

```
1. Dari katalog: klik "Bandingkan" di kartu produk (toggle)
2. Dari detail produk: klik "Bandingkan" di bawah harga
3. Maksimal 4 produk
4. Klik link "Bandingkan" di header/nav → tabel perbandingan side-by-side
5. Fitur: bandingkan harga, brand, kategori, SKU, stok, rating, spesifikasi
6. Tombol "Tambah ke Keranjang" langsung dari tabel compare
```

### 5. Retur Barang (Customer)

```
1. Buka detail pesanan → klik "Ajukan Retur"
2. Pilih alasan: Produk Cacat / Tidak Sesuai / Rusak Saat Kirim / Lainnya
3. Upload foto bukti (min 1, maks 5, format JPG/PNG, max 2MB per foto)
4. Kirim → status retur "pending" → admin dapat notifikasi database + WA

Flow Status Retur:
  pending (Menunggu)
    ↓ admin setujui → notif database + email + WA ke customer
  approved (Disetujui)
    ↓
  rejected (Ditolak) — admin wajib kasih alasan → notif database + email + WA ke customer
```

### 6. Refund Management (Terpisah dari Retur)

```
1. Admin membuat refund di menu "Transaksi → Refund"
2. Pilih pesanan, masukkan jumlah, alasan, metode refund (transfer/tunai)
3. Status workflow: pending → approved → processed → completed
4. Bisa ditolak (rejected) dari pending/approved
```

### 7. Admin Panel (Filament v5)

```
Menu Navigasi:

Katalog:
  ├─ Kategori           → CRUD kategori (parent_id, slug otomatis, helper text)
  ├─ Produk             → CRUD produk (gambar, stok, harga, brand, slug, SKU, helper text)
  └─ Ulasan Produk      → CRUD ulasan & rating dari customer

Persediaan:
  ├─ Stok               → Read-only audit log pergerakan stok
  ├─ Pemasok            → CRUD supplier (nama, kontak, alamat, helper text)
  └─ Purchase Order     → CRUD PO (pilih supplier, produk, quantity + price otomatis, total)
                           Status: draft → ordered → partially_received → received (tambah stok + stock_movement) → cancelled

Transaksi:
  ├─ POS                → POS interface (product grid, cart, customer, payment via cash/bank_transfer)
  │                       → Diskon per item/total (% atau nominal)
  │                       → Tambah customer cepat via modal
  │                       → Riwayat transaksi POS hari ini (collapsible + auto-refresh)
  │                       → Input quantity langsung (klik angka → input → Enter/blur simpan)
  │                       → Shortcut keyboard: F2 (cari), F3 (bayar), Esc (hapus)
  │                       → Virtual numpad untuk input harga/jumlah
  │                       → Tombol Keluar + hidden Dashboard untuk role Kasir
  │                       → Order number prefix `POS-`, status langsung `completed`
  │                       → Stok otomatis berkurang + stock_movement tercatat
  ├─ Pesanan            → Lihat/ubah status, filter (status), aksi per-item:
  │                       ├─ Konfirmasi Bayar (notif email + WA customer)
  │                       ├─ Proses (notif email + WA customer)
  │                       ├─ Kirim (input kurir + resi, notif email + WA customer)
  │                       ├─ Selesai (notif email + WA customer)
  │                       ├─ Batalkan (notif email + WA customer)
  │                       └─ Edit
  │                       → Export CSV
  ├─ Retur               → Lihat pengajuan + foto bukti
  │                        ├─ Setujui (notif database + email + WA customer)
  │                        └─ Tolak + catatan wajib (notif database + email + WA customer)
  └─ Refund              → CRUD refund management (terpisah dari retur)
                           Status: pending → approved → processed → completed / rejected

Laporan:
  └─ Laba Rugi           → Filter bulan/tahun, stat cards (Net Revenue, COGS, Gross Profit, Net Profit)
                           Rincian laba rugi, arus kas, pendapatan harian, expense breakdown
                           CSV export

Konten:
  ├─ Banner              → CRUD banner slider (gambar, link, judul, helper text)
  ├─ Halaman             → CRUD halaman statis (tentang kami, kebijakan privasi, dll)
  └─ Pengeluaran         → CRUD catatan pengeluaran toko (kategori, jumlah, helper text)

Pelanggan:
  └─ Pelanggan           → Read-only daftar customer + detail + riwayat pesanan

Promo:
  └─ Kupon               → CRUD kupon diskon (kode, tipe, nilai, min order, helper text)

Layanan Pelanggan:
  └─ Chat                → Lihat percakapan + balas + tutup (notifikasi database ke admin)

Pengaturan:
  ├─ Rekening Bank       → CRUD rekening (aktif/nonaktif, urutan, helper text)
  ├─ Dashboard           → Statistik (7 card), grafik pendapatan 30 hari, grafik pergerakan stok,
  │                        top 10 produk terlaris, slow-moving products, top 10 loyal customers
  ├─ Pengaturan Toko     → Nama toko, deskripsi, email, telepon, alamat, footer, WhatsApp,
  │                        jam operasional, toggle libur + pesan libur, flash sale text, SEO meta,
  │                        SMTP config, RajaOngkir config, Midtrans config, Fonnte WA config,
  │                        Loyalty points config

Sistem:
  ├─ Pengguna            → CRUD user admin (multi-role: Super Admin, Stok, Keuangan, Kasir)
  ├─ Roles               → CRUD roles Spatie
  ├─ Aktivitas Admin     → Read-only audit log aktivitas admin (created/updated/deleted model)
  ├─ Backup Database     → Create + download + delete backup SQL
  └─ Broadcast           → Kirim broadcast email/WhatsApp ke customer (pilih channel + recipient filter)
```

### 8. SEO

```
- Dynamic <title> per halaman (via @section('title'))
- Meta description (via @section('meta_description'))
- Meta keywords (konfigurasi dari settings)
- Open Graph tags: og:title, og:description, og:image, og:type, og:url (via @section)
- Meta robots (index/follow default)
- Sitemap.xml otomatis di GET /sitemap.xml (products, categories, pages)
- Schema.org Organization di layout head (JSON-LD via @php + json_encode)
- Schema.org BreadcrumbList di halaman produk
- Schema.org Product markup di halaman detail produk (name, price, availability, review)
- Catatan: SEMUA JSON-LD harus via @php + json_encode() — tidak boleh inline {} karena konflik Livewire
```

---

## Fitur Lengkap (A-Z)

| Fitur | Status | Keterangan |
|-------|--------|------------|
| Admin Activity Log (Audit) | ✅ Selesai | Auto-log created/updated/deleted untuk 10 model admin + read-only resource |
| Auth admin (Filament) | ✅ Selesai | canAccessPanel via isAdmin |
| Auth customer (Breeze) | ✅ Selesai | Login, register, forgot/reset password, email verify |
| Backup Database | ✅ Selesai | Artisan command + Filament page (create, download, delete) |
| Banner slider | ✅ Selesai | CRUD admin + tampil di home |
| Broadcast WA/Email | ✅ Selesai | Broadcast ke customer via email/WA/both, filter aktif/tidak aktif |
| Cart (guest + logged in) | ✅ Selesai | Session-based untuk guest, user_id untuk login |
| Chat / Live Chat | ✅ Selesai | Customer → admin, real-time polling 5 detik, notif database admin |
| Checkout | ✅ Selesai | Alamat, kurir (4), ongkir realtime Alpine + RajaOngkir API, bank transfer, Midtrans, kupon, poin |
| Compare Products | ✅ Selesai | Session-based (max 4), side-by-side table, toggle dari katalog & detail |
| Coupon diskon | ✅ Selesai | Percentage/fixed, min order, max uses, expiry, apply di checkout |
| Customer management | ✅ Selesai | Read-only admin, detail + order history |
| Dashboard admin | ✅ Selesai | 7 stat cards, 2 chart widget, 3 tabel widget (top products, slow moving, loyal customers) |
| Expense | ✅ Selesai | CRUD pengeluaran toko |
| Export CSV | ✅ Selesai | Pesanan, produk, supplier, laba rugi |
| Footer pages dinamis | ✅ Selesai | Halaman statis diatur dari admin, tampil di footer |
| Frequently Bought Together | ✅ Selesai | Algoritma subquery self-join order_items, tampil di detail produk |
| Guest checkout | ✅ Selesai | Checkout tanpa registrasi, order tertaut setelah daftar |
| Header settings | ✅ Selesai | Jam operasional, toggle libur + pesan libur, flash sale text |
| Helper text admin | ✅ Selesai | Semua form Filament ada helper text deskriptif |
| Halaman Statis (Pages) | ✅ Selesai | CRUD halaman (tentang kami, kebijakan, dll) via admin + storefront route |
| Kategori produk | ✅ Selesai | CRUD dengan parent_id untuk sub-kategori |
| Keranjang | ✅ Selesai | Qty control, subtotal, total, validasi stok |
| Kupon diskon | ✅ Selesai | Apply di checkout + admin CRUD |
| Laporan Laba Rugi | ✅ Selesai | Filter bulan/tahun, stat cards, rincian, arus kas, CSV export |
| Live Search Suggestion | ✅ Selesai | Alpine.js + JSON endpoint, dropdown real-time (nama, brand, harga, gambar) |
| Loyalty Points | ✅ Selesai | Poin per transaksi, redeem di checkout, bonus referral, histori |
| Migrasi database | ✅ Selesai | 47 migration, 30+ tabel lengkap dengan relasi |
| Midtrans payment | ✅ Selesai | Snap redirect (Kartu Kredit, VA, Convenience Store, QRIS, E-Wallet) |
| Multi-role admin | ✅ Selesai | Super Admin, Stok, Keuangan, Kasir (Spatie Permission) |
| Notifikasi database admin | ✅ Selesai | Upload bayar, retur baru, chat baru (Filament Notification) |
| Notifikasi email admin | ✅ Selesai | PaymentUploaded, ReturnSubmitted, Broadcast (via queue) |
| Notifikasi email customer | ✅ Selesai | OrderStatusChanged, ReturnStatusChanged (via queue) |
| Notifikasi WhatsApp | ✅ Selesai | Fonnte API — status pesanan, retur, broadcast (via queue) |
| Pesanan (admin) | ✅ Selesai | CRUD + actions + filter + export + kurir + resi |
| Pesanan (customer) | ✅ Selesai | Daftar, detail, upload bayar, konfirmasi terima, ulasan |
| POS (Point of Sale) | ✅ Selesai | Interface kasir, stok auto-decrement, order completed langsung |
| Produk (admin) | ✅ Selesai | CRUD + gambar + stok + slug otomatis + promo price |
| Produk (storefront) | ✅ Selesai | Katalog, detail, search, filter kategori, ulasan rating |
| Purchase Order | ✅ Selesai | PO ke supplier, received → auto tambah stok |
| Queue | ✅ Selesai | Database driver untuk notifikasi |
| RajaOngkir API | ✅ Selesai | Service class, AJAX endpoint, fallback statis jika API tidak dikonfigurasi |
| Refund Management | ✅ Selesai | CRUD terpisah dari retur, workflow pending→approved→processed→completed |
| Retur (admin) | ✅ Selesai | Setujui/tolak + lihat foto + notifikasi + WA |
| Retur (customer) | ✅ Selesai | Ajukan retur + upload foto + notifikasi admin |
| Seeder data sample | ✅ Selesai | 2 user, 45 produk, 8 kategori, 6 brand, 3 bank, 1 kupon, 12+ settings, roles |
| SEO | ✅ Selesai | Meta tags, OG, sitemap XML, Schema.org JSON-LD (Organization, BreadcrumbList, Product) |
| Settings toko | ✅ Selesai | ManageSettings page (nama, kontak, jam, flash sale, SMTP, RajaOngkir, Midtrans, Fonnte, Loyalty) |
| SMTP Konfigurasi | ✅ Selesai | Admin bisa setting email (host, port, username, password, enkripsi, dari) tanpa edit `.env` |
| Stock management | ✅ Selesai | Stock movement log, otomatis saat order/PO received |
| Supplier management | ✅ Selesai | CRUD pemasok |
| Testing | ✅ Selesai | 25 test (Feature Auth + Profile + basic) via SQLite memory |
| Ulasan Produk | ✅ Selesai | Rating & komentar setelah pesanan selesai, tampil di detail produk |
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

### Cart, Compare & Wishlist View Composer
- Jumlah item keranjang, item compare, status wishlist disediakan via `View::composer` di `AppServiceProvider`
- Tidak perlu query manual di setiap Blade template

### Notifikasi
- Customer: hanya `mail` *channel* — tidak punya Filament bell
- Admin: `Filament\Notifications\Notification::make()->sendToDatabase()` — database-only
- Admin juga dapet email via `PaymentUploaded`, `ReturnSubmitted`, `BroadcastNotification`
- Semua notifikasi implement `ShouldQueue` & `Queueable` untuk performa

### AdminActivityLogger
- Gunakan curly-brace dynamic call: `AdminActivityLogger::{$action}($model)`
- Auto-register untuk 10 model admin via event listener di `AppServiceProvider`
- Hanya mencatat jika user terautentikasi dan is_admin = true

---

## Akun Default (*Seeder*)

| Role | Email | Password |
|------|-------|----------|
| Super Admin | `admin@procell.com` | `admin123` |
| Customer | `customer@procell.com` | `customer123` |

## Data Sample (*Seeder*)

- **2 User**: Admin (Super Admin role) + Customer
- **8 Kategori**: LCD & Display, Battery, Flexible Cable, Mainboard & IC, Button & Switch, Charger & Adapter, Data Cable, Accessories
- **45 Produk** tersebar di 8 kategori, 6 brand (Samsung, iPhone, Xiaomi, OPPO, Vivo, Realme)
- **3 Bank**: Mandiri (1234567890), BCA (0987654321), BRI (5556667777)
- **33+ Settings**: Nama toko (ProCell Store), deskripsi, email, telepon, alamat, footer, WhatsApp, jam operasional, *flash sale text*, SEO meta, SMTP, RajaOngkir, Midtrans, Fonnte, Loyalty, dll
- **1 Kupon Demo**: Kode `Pro-Diskon 30%` — diskon 30%, minimal belanja Rp0, berlaku 1 tahun
- **4 Roles**: Super Admin, Stok, Keuangan, Kasir (Spatie Permission)
- **3 Pages Default**: Tentang Kami, Kebijakan Privasi, Syarat & Ketentuan

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
| POS `_history.blade.php` `$order['total']` undefined saat render awal | Gunakan `grand_total` (nama accessor model) + mapping array konsisten di `index()` dan `history()` |
| `Payment_method` di tabel admin hanya tampil `-` selain `bank_transfer` | Tambah `'cash' => 'Tunai'` dan `'midtrans' => 'Midtrans'` di `OrdersTable.php` dan `ExportController.php` |
| GROUP BY error `only_full_group_by` di TopProductsTableWidget | Subquery di `DB::raw('(...) as sold')` bukan LEFT JOIN + GROUP BY |
| ProfitLossReport CSV tidak bisa dari Livewire | Export via route (`ExportController::profitLossCsv`) bukan dari Livewire action |
| `sum('total')` column not found di `purchase_orders` | Kolom namanya `total_amount`, bukan `total` — fix di `ProfitLossReport.php` & `ExportController.php` |

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
- Fonnte (WhatsApp): admin setting API Key di Pengaturan Toko → WhatsApp (Fonnte). Notifikasi otomatis ke customer via WA saat status pesanan/retur berubah, plus notifikasi ke admin saat retur baru / bukti bayar diupload
- POS: tersedia di `/admin/pos`, hanya untuk role Kasir + Super Admin + Stok + Keuangan. Kasir tidak punya akses ke Filament panel, langsung redirect ke POS. Tombol Dashboard disembunyikan untuk Kasir
- Guest checkout: order disimpan di session `guest_orders`. Setelah registrasi, order otomatis tertaut ke akun baru berdasarkan email
- Compare: session-based (max 4 produk), tombol di kartu produk & detail produk, badge di header
- Chat: polling 5 detik via Alpine.js, notifikasi database ke admin, admin balas lewat Filament
- Live search: Alpine.js fetch ke endpoint JSON, debounce 300ms, muncul sebagai dropdown
- Refund: terpisah dari retur, workflow pending→approved→processed→completed→rejected
- Audit log: auto-log via model events di AppServiceProvider, read-only di admin
- Backup: export semua tabel MySQL ke file .sql di `storage/app/backups/`, download via route

---

## *Roadmap* / Selanjutnya

### ✅ Terselesaikan (Phase 1 — Dasar)
- Scaffolding Laravel Breeze + instalasi Filament v5
- Desain database (30+ tabel) + migrasi
- Storefront: home, katalog, detail produk, keranjang, checkout, pesanan, retur
- Admin panel CRUD: kategori, produk, bank, banner, kupon, pengeluaran, pemasok, PO, retur
- Dashboard admin: 4 widget (stats, revenue, stok)
- SEO: meta tags, OG, sitemap XML, Schema.org
- Wishlist, Guest Checkout, Midtrans Payment
- Multi-role admin (Spatie)
- POS interface
- Notifikasi email + WhatsApp (Fonnte)
- Loyalty points + referral system
- Halaman statis (Pages)
- Ulasan produk (Reviews)
- RajaOngkir API

### ✅ Terselesaikan (Phase 2 — Lanjutan)
- **#6 Live Search Suggestion** — Alpine.js fetch + JSON endpoint
- **#8 Frequently Bought Together** — algoritma subquery order_items self-join
- **Dashboard Analytics** — Top Products, Slow Moving, Loyal Customers, Gross Profit, AOV, Conversion Rate
- **#1 Laporan Laba Rugi / Cash Flow** — Filter bulan/tahun, CSV export
- **#2 Refund Management** — Terpisah dari retur, workflow lengkap
- **#3 Audit Log Admin** — Auto-log model events, read-only resource
- **#4 Backup Database** — Artisan command + Filament page
- **#5 Broadcast WhatsApp/Email** — Pilih channel + recipient
- **#7 Compare Products** — Session-based (max 4), side-by-side
- **#9 Chat / Live Chat** — Customer ↔ Admin, polling 5 detik

### ⬜ Selanjutnya (Phase 3 — Rencana)
- **#10 Restock Notification ("Notify Me")** — Customer request notifikasi saat produk tersedia
- **#11 Flash Sale Countdown Timer** — Timer hitung mundur di produk flash sale
- **#12 Bundling / Paket Produk** — Paket hemat beberapa produk
- **#13 Hold Order (POS)** — Tahan pesanan sementara di POS
- **#14 Split Payment (POS)** — Bayar dengan 2 metode berbeda
- **#15 Cash Drawer (POS)** — Integrasi laci uang
- **#16 Shift Kasir** — Buka/tutup shift, setoran awal
- **#17 Return via POS** — Retur barang langsung dari POS
- **#18 Membership Tier (Silver/Gold/Platinum)** — Tier membership dengan benefit berbeda

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
