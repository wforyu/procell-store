<?php

namespace App\Filament\Pages;

use App\Models\Expense;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PurchaseOrder;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\DB;

class ProfitLossReport extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBarSquare;

    protected string $view = 'filament.pages.profit-loss-report';

    protected static ?string $navigationLabel = 'Laba Rugi & Arus Kas';

    protected static ?int $navigationSort = 1;

    protected static ?string $title = 'Laporan Laba Rugi & Arus Kas';

    public ?string $month = null;

    public ?string $year = null;

    public array $stats = [];

    public array $cashFlow = [];

    public array $dailyRevenue = [];

    public array $expenseBreakdown = [];

    public function mount(): void
    {
        $this->month = request('month', now()->format('m'));
        $this->year = request('year', now()->format('Y'));
        $this->loadData();
    }

    public function filter(): void
    {
        $this->loadData();
    }

    public function loadData(): void
    {
        $month = $this->month;
        $year = $this->year;

        $totalRevenue = Order::whereIn('status', ['completed', 'shipped'])
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->sum('total_amount');

        $totalShipping = Order::whereIn('status', ['completed', 'shipped'])
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->sum('shipping_cost');

        $totalDiscount = Order::whereIn('status', ['completed', 'shipped'])
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->sum(DB::raw('COALESCE(discount_amount, 0) + COALESCE(points_discount, 0)'));

        $netRevenue = $totalRevenue + $totalShipping - $totalDiscount;

        $totalCogs = OrderItem::whereHas('order', function ($q) use ($month, $year) {
            $q->whereIn('status', ['completed', 'shipped'])
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year);
        })->join('products', 'order_items.product_id', '=', 'products.id')
            ->select(DB::raw('SUM(order_items.quantity * products.buying_price) as total_cogs'))
            ->value('total_cogs') ?? 0;

        $totalExpenses = Expense::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->sum('amount');

        $purchasesTotal = PurchaseOrder::where('status', 'received')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->sum('total_amount');

        $totalOrders = Order::whereIn('status', ['completed', 'shipped'])
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->count();

        $grossProfit = $netRevenue - $totalCogs;
        $netProfit = $grossProfit - $totalExpenses;

        $this->stats = [
            'total_revenue' => $totalRevenue,
            'total_shipping' => $totalShipping,
            'total_discount' => $totalDiscount,
            'net_revenue' => $netRevenue,
            'total_cogs' => $totalCogs,
            'gross_profit' => $grossProfit,
            'gross_margin' => $netRevenue > 0 ? round(($grossProfit / $netRevenue) * 100, 1) : 0,
            'total_expenses' => $totalExpenses,
            'net_profit' => $netProfit,
            'net_margin' => $netRevenue > 0 ? round(($netProfit / $netRevenue) * 100, 1) : 0,
            'total_orders' => $totalOrders,
            'aov' => $totalOrders > 0 ? $netRevenue / $totalOrders : 0,
        ];

        $this->cashFlow = [
            'inflow' => $netRevenue,
            'outflow_operational' => $totalExpenses,
            'outflow_purchases' => $purchasesTotal,
            'total_outflow' => $totalExpenses + $purchasesTotal,
            'net_cash_flow' => $netRevenue - ($totalExpenses + $purchasesTotal),
        ];

        $this->dailyRevenue = Order::whereIn('status', ['completed', 'shipped'])
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as revenue'), DB::raw('COUNT(*) as order_count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->toArray();

        $this->expenseBreakdown = Expense::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->orderByDesc('total')
            ->get()
            ->toArray();
    }

    public static function getNavigationGroup(): string
    {
        return 'Laporan';
    }

    public static function getNavigationItems(array $urlParameters = []): array
    {
        return array_map(fn ($item) => $item->extraAttributes(['title' => 'Laporan laba rugi, arus kas, dan keuntungan toko']), parent::getNavigationItems($urlParameters));
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportCsv')
                ->label('Export CSV')
                ->icon('heroicon-m-arrow-down-tray')
                ->color('success')
                ->url(fn (): string => route('admin.reports.profit-loss.csv', ['month' => $this->month, 'year' => $this->year])),
        ];
    }

    protected function getMonths(): array
    {
        return [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];
    }
}
