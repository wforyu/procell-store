<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
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
                    number_format((int) $order->total_amount, 0, ',', '.'),
                    number_format((int) ($order->shipping_cost ?? 0), 0, ',', '.'),
                    number_format((int) ($order->discount_amount ?? 0), 0, ',', '.'),
                    number_format((int) $order->grand_total, 0, ',', '.'),
                    match ($order->payment_method) {
                        'bank_transfer' => 'Transfer Bank',
                        'cash' => 'Tunai',
                        'midtrans' => 'Midtrans',
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
                    'Rp '.number_format((int) $product->buying_price, 0, ',', '.'),
                    'Rp '.number_format((int) $product->selling_price, 0, ',', '.'),
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

    public function profitLossCsv(Request $request)
    {
        $month = $request->input('month', now()->format('m'));
        $year = $request->input('year', now()->format('Y'));
        $monthName = Carbon::createFromDate($year, $month, 1)->locale('id')->isoFormat('MMMM YYYY');

        $totalRevenue = Order::whereIn('status', ['completed', 'shipped'])
            ->whereMonth('created_at', $month)->whereYear('created_at', $year)
            ->sum('total_amount');

        $totalShipping = Order::whereIn('status', ['completed', 'shipped'])
            ->whereMonth('created_at', $month)->whereYear('created_at', $year)
            ->sum('shipping_cost');

        $totalDiscount = Order::whereIn('status', ['completed', 'shipped'])
            ->whereMonth('created_at', $month)->whereYear('created_at', $year)
            ->sum(DB::raw('COALESCE(discount_amount, 0) + COALESCE(points_discount, 0)'));

        $netRevenue = $totalRevenue + $totalShipping - $totalDiscount;

        $totalCogs = OrderItem::whereHas('order', fn ($q) => $q->whereIn('status', ['completed', 'shipped'])
            ->whereMonth('created_at', $month)->whereYear('created_at', $year))
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select(DB::raw('SUM(order_items.quantity * products.buying_price) as total_cogs'))
            ->value('total_cogs') ?? 0;

        $totalExpenses = Expense::whereMonth('date', $month)->whereYear('date', $year)->sum('amount');

        $purchasesTotal = PurchaseOrder::where('status', 'received')
            ->whereMonth('created_at', $month)->whereYear('created_at', $year)
            ->sum('total_amount');

        $totalOrders = Order::whereIn('status', ['completed', 'shipped'])
            ->whereMonth('created_at', $month)->whereYear('created_at', $year)
            ->count();

        $grossProfit = $netRevenue - $totalCogs;
        $netProfit = $grossProfit - $totalExpenses;
        $margin = $netRevenue > 0 ? round(($netProfit / $netRevenue) * 100, 1) : 0;
        $aov = $totalOrders > 0 ? round($netRevenue / $totalOrders) : 0;

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="laba-rugi-'.$year.'-'.$month.'.csv"',
        ];

        $callback = function () use ($monthName, $totalRevenue, $totalShipping, $totalDiscount, $netRevenue, $totalCogs, $grossProfit, $totalExpenses, $netProfit, $margin, $totalOrders, $aov, $purchasesTotal) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, ['Laporan Laba Rugi & Arus Kas - '.$monthName]);
            fputcsv($handle, []);
            fputcsv($handle, ['Metrik', 'Nilai']);
            fputcsv($handle, ['Total Pendapatan', 'Rp '.number_format($totalRevenue, 0, ',', '.')]);
            fputcsv($handle, ['Biaya Pengiriman', 'Rp '.number_format($totalShipping, 0, ',', '.')]);
            fputcsv($handle, ['Diskon', 'Rp '.number_format($totalDiscount, 0, ',', '.')]);
            fputcsv($handle, ['Pendapatan Bersih', 'Rp '.number_format($netRevenue, 0, ',', '.')]);
            fputcsv($handle, ['HPP (COGS)', 'Rp '.number_format($totalCogs, 0, ',', '.')]);
            fputcsv($handle, ['Laba Kotor', 'Rp '.number_format($grossProfit, 0, ',', '.')]);
            fputcsv($handle, ['Biaya Operasional', 'Rp '.number_format($totalExpenses, 0, ',', '.')]);
            fputcsv($handle, ['Laba Bersih', 'Rp '.number_format($netProfit, 0, ',', '.')]);
            fputcsv($handle, ['Margin Laba', $margin.'%']);
            fputcsv($handle, ['Total Pesanan', $totalOrders]);
            fputcsv($handle, ['Rata-rata Pesanan (AOV)', 'Rp '.number_format($aov, 0, ',', '.')]);
            fputcsv($handle, []);
            fputcsv($handle, ['ARUS KAS', '']);
            fputcsv($handle, ['Pemasukan', 'Rp '.number_format($netRevenue, 0, ',', '.')]);
            fputcsv($handle, ['Pengeluaran Operasional', 'Rp '.number_format($totalExpenses, 0, ',', '.')]);
            fputcsv($handle, ['Pembelian Stok', 'Rp '.number_format($purchasesTotal, 0, ',', '.')]);
            fputcsv($handle, ['Total Pengeluaran', 'Rp '.number_format($totalExpenses + $purchasesTotal, 0, ',', '.')]);
            fputcsv($handle, ['Arus Kas Bersih', 'Rp '.number_format($netRevenue - ($totalExpenses + $purchasesTotal), 0, ',', '.')]);

            fclose($handle);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function downloadBackup(string $filename)
    {
        $path = storage_path('app/backups/'.$filename);
        if (! File::exists($path)) {
            abort(404, 'File backup tidak ditemukan');
        }

        return Response::download($path);
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
