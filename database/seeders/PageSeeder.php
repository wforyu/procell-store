<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'Cara Order',
                'slug' => 'cara-order',
                'content' => '<h2>Cara Berbelanja di ProCell Store</h2>
<p>Berikut adalah langkah-langkah mudah untuk berbelanja di toko kami:</p>
<ol>
<li><strong>Pilih Produk</strong> &mdash; Cari produk yang Anda butuhkan melalui katalog atau fitur pencarian.</li>
<li><strong>Tambahkan ke Keranjang</strong> &mdash; Klik tombol "Tambah ke Keranjang" pada halaman detail produk.</li>
<li><strong>Masuk ke Akun</strong> &mdash; Login atau daftar akun terlebih dahulu untuk melanjutkan ke checkout.</li>
<li><strong>Checkout</strong> &mdash; Isi alamat pengiriman, pilih kurir dan layanan pengiriman, lalu pilih metode pembayaran.</li>
<li><strong>Upload Bukti Transfer</strong> &mdash; Lakukan pembayaran ke rekening yang tertera dan upload bukti transfer di halaman pesanan.</li>
<li><strong>Tunggu Konfirmasi</strong> &mdash; Admin akan memverifikasi pembayaran dan memproses pesanan Anda.</li>
<li><strong>Terima Pesanan</strong> &mdash; Konfirmasi penerimaan barang setelah paket sampai dengan selamat.</li>
</ol>
<p>Jika ada pertanyaan, jangan ragu untuk menghubungi CS kami melalui WhatsApp.</p>',
                'is_active' => true,
            ],
            [
                'title' => 'Syarat & Ketentuan',
                'slug' => 'syarat-ketentuan',
                'content' => '<h2>Syarat & Ketentuan</h2>
<p>Dengan berbelanja di ProCell Store, Anda menyetujui syarat dan ketentuan berikut:</p>
<h3>Ketersediaan Produk</h3>
<p>Kami berusaha menjaga ketersediaan stok produk yang ditampilkan. Namun, ada kalanya produk yang dipesan sedang tidak tersedia. Dalam kasus tersebut, kami akan menghubungi Anda untuk mengganti atau membatalkan pesanan.</p>
<h3>Harga</h3>
<p>Harga tercantum dalam Rupiah (IDR) dan sudah termasuk PPN. Harga dapat berubah sewaktu-waktu tanpa pemberitahuan sebelumnya.</p>
<h3>Pembayaran</h3>
<p>Pembayaran dilakukan melalui transfer bank ke rekening yang tertera. Pesanan akan diproses setelah pembayaran terverifikasi.</p>
<h3>Pengiriman</h3>
<p>Pengiriman dilakukan setelah pesanan selesai diproses. Waktu pengiriman tergantung pada layanan kurir yang dipilih.</p>',
                'is_active' => true,
            ],
            [
                'title' => 'Kebijakan Privasi',
                'slug' => 'kebijakan-privasi',
                'content' => '<h2>Kebijakan Privasi</h2>
<p>ProCell Store menghargai privasi Anda. Kebijakan ini menjelaskan bagaimana kami mengumpulkan, menggunakan, dan melindungi informasi pribadi Anda.</p>
<h3>Informasi yang Kami Kumpulkan</h3>
<ul>
<li>Nama lengkap</li>
<li>Alamat email</li>
<li>Nomor telepon</li>
<li>Alamat pengiriman</li>
<li>Riwayat transaksi</li>
</ul>
<h3>Penggunaan Informasi</h3>
<p>Informasi yang kami kumpulkan digunakan untuk memproses pesanan, mengirimkan notifikasi terkait pesanan, dan meningkatkan layanan kami.</p>
<h3>Perlindungan Data</h3>
<p>Kami menerapkan langkah-langkah keamanan untuk melindungi informasi pribadi Anda dari akses yang tidak sah.</p>',
                'is_active' => true,
            ],
            [
                'title' => 'Pengembalian Barang',
                'slug' => 'pengembalian-barang',
                'content' => '<h2>Kebijakan Pengembalian Barang</h2>
<p>Kami menerima pengajuan retur untuk produk dengan ketentuan berikut:</p>
<h3>Waktu Pengajuan</h3>
<p>Retur dapat diajukan maksimal 3 hari setelah pesanan diterima atau setelah status pesanan "Dikirim".</p>
<h3>Alasan Retur</h3>
<ul>
<li>Produk cacat atau rusak</li>
<li>Produk tidak sesuai dengan pesanan</li>
<li>Rusak saat pengiriman</li>
<li>Alasan lainnya (akan dipertimbangkan oleh admin)</li>
</ul>
<h3>Syarat Retur</h3>
<ul>
<li>Melampirkan foto bukti yang jelas (minimal 1 foto)</li>
<li>Barang dalam kondisi lengkap (termasuk aksesoris dan kemasan jika ada)</li>
<li>Belum pernah dimodifikasi atau diperbaiki</li>
</ul>
<h3>Proses Retur</h3>
<ol>
<li>Ajukan retur melalui halaman detail pesanan</li>
<li>Admin akan meninjau pengajuan Anda</li>
<li>Jika disetujui, kami akan memberikan instruksi pengembalian barang</li>
<li>Barang akan diperiksa dan pengembalian dana/penggantian barang akan diproses</li>
</ol>',
                'is_active' => true,
            ],
        ];

        foreach ($pages as $page) {
            Page::create($page);
        }

        $this->command->info('Halaman default berhasil dibuat!');
    }
}
