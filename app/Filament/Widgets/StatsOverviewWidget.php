<?php

namespace App\Filament\Widgets;

use App\Models\Expense;
use App\Models\Order;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalRevenue = Order::whereIn('status', ['completed', 'shipped'])->sum('total_amount');
        $totalExpenses = Expense::sum('amount');
        $totalProducts = Product::count();
        $lowStockProducts = Product::whereColumn('stock', '<=', 'min_stock')->count();

        return [
            Stat::make('Total Pendapatan', 'Rp '.number_format($totalRevenue, 0, ',', '.'))
                ->description('Dari pesanan selesai')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),
            Stat::make('Total Pengeluaran', 'Rp '.number_format($totalExpenses, 0, ',', '.'))
                ->description('Semua biaya operasional')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('danger'),
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
