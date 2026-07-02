<?php

namespace App\Filament\Widgets;

use App\Models\Expense;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalRevenue = Order::whereIn('status', ['completed', 'shipped'])->sum('total_amount');
        $totalExpenses = Expense::sum('amount');
        $totalProducts = Product::count();
        $lowStockProducts = Product::whereColumn('stock', '<=', 'min_stock')->count();

        $totalOrders = Order::whereIn('status', ['completed', 'shipped'])->count();
        $aov = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        $totalVisits = cache()->get('total_visits', 0);
        $conversionRate = $totalVisits > 0 ? round(($totalOrders / $totalVisits) * 100, 2) : 0;

        $totalCogs = OrderItem::whereHas('order', function ($q) {
            $q->whereIn('status', ['completed', 'shipped']);
        })->join('products', 'order_items.product_id', '=', 'products.id')
            ->select(DB::raw('SUM(order_items.quantity * products.buying_price) as total_cogs'))
            ->value('total_cogs') ?? 0;
        $totalProfit = $totalRevenue - $totalCogs;
        $profitMargin = $totalRevenue > 0 ? round(($totalProfit / $totalRevenue) * 100, 1) : 0;

        return [
            Stat::make('Total Pendapatan', 'Rp '.number_format($totalRevenue, 0, ',', '.'))
                ->description('Dari pesanan selesai')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),
            Stat::make('Total Pengeluaran', 'Rp '.number_format($totalExpenses, 0, ',', '.'))
                ->description('Semua biaya operasional')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('danger'),
            Stat::make('Laba Kotor', 'Rp '.number_format($totalProfit, 0, ',', '.'))
                ->description("Margin {$profitMargin}% dari pendapatan")
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color($profitMargin > 20 ? 'success' : 'warning'),
            Stat::make('Rata-rata Pesanan (AOV)', 'Rp '.number_format($aov, 0, ',', '.'))
                ->description("{$totalOrders} pesanan selesai")
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('info'),
            Stat::make('Rate Konversi', "{$conversionRate}%")
                ->description('Dari total kunjungan')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),
            Stat::make('Total Produk', $totalProducts)
                ->description('Semua produk di database')
                ->descriptionIcon('heroicon-m-cube')
                ->color('info'),
            Stat::make('Stok Menipis', $lowStockProducts)
                ->description('Produk dengan stok <= min stok')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('warning'),
        ];
    }
}
