<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ExportController extends Controller
{
    public function ordersCsv(Request $request)
    {
        $orders = Order::with('user')->orderBy('created_at', 'desc')->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="orders-'.now()->format('Ymd-His').'.csv"',
        ];

        $callback = function () use ($orders) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'No. Pesanan', 'Pelanggan', 'Status', 'Total Produk', 'Ongkir',
                'Diskon', 'Grand Total', 'Pembayaran', 'Kurir', 'No. Resi',
                'Alamat', 'Tgl Dibuat',
            ]);

            foreach ($orders as $order) {
                fputcsv($handle, [
                    $order->order_number,
                    $order->user?->name ?? '-',
                    match ($order->status) {
                        'pending' => 'Menunggu Bayar',
                        'waiting_confirmation' => 'Menunggu Konfirmasi',
                        'processing' => 'Diproses',
                        'shipped' => 'Dikirim',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                        default => $order->status,
                    },
                    $order->total_amount,
                    $order->shipping_cost ?? 0,
                    $order->discount_amount ?? 0,
                    $order->grand_total,
                    match ($order->payment_method) {
                        'bank_transfer' => 'Transfer Bank',
                        default => $order->payment_method ?? '-',
                    },
                    $order->courier,
                    $order->tracking_number ?? '-',
                    $order->shipping_address,
                    $order->created_at->format('d/m/Y H:i'),
                ]);
            }

            fclose($handle);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function productsCsv(Request $request)
    {
        $products = Product::with('category')->orderBy('name')->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="products-'.now()->format('Ymd-His').'.csv"',
        ];

        $callback = function () use ($products) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'Nama Produk', 'Kategori', 'Brand', 'SKU',
                'Harga Beli', 'Harga Jual', 'Stok', 'Min Stok',
                'Rating', 'Jumlah Review', 'Aktif',
            ]);

            foreach ($products as $product) {
                fputcsv($handle, [
                    $product->name,
                    $product->category?->name ?? '-',
                    $product->brand ?? '-',
                    $product->sku,
                    $product->buying_price,
                    $product->selling_price,
                    $product->stock,
                    $product->min_stock,
                    $product->avg_rating ?? 0,
                    $product->review_count ?? 0,
                    $product->is_active ? 'Ya' : 'Tidak',
                ]);
            }

            fclose($handle);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function suppliersCsv(Request $request)
    {
        $suppliers = Supplier::orderBy('name')->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="suppliers-'.now()->format('Ymd-His').'.csv"',
        ];

        $callback = function () use ($suppliers) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'Nama Supplier', 'Kontak Person', 'Telepon', 'Email',
                'Alamat', 'Catatan', 'Aktif',
            ]);

            foreach ($suppliers as $supplier) {
                fputcsv($handle, [
                    $supplier->name,
                    $supplier->contact_person ?? '-',
                    $supplier->phone ?? '-',
                    $supplier->email ?? '-',
                    $supplier->address ?? '-',
                    $supplier->notes ?? '-',
                    $supplier->is_active ? 'Ya' : 'Tidak',
                ]);
            }

            fclose($handle);
        };

        return Response::stream($callback, 200, $headers);
    }
}
