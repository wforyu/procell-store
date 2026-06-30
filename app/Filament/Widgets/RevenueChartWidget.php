<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class RevenueChartWidget extends ChartWidget
{
    public ?string $heading = 'Grafik Pendapatan (Bulan Ini)';

    protected function getData(): array
    {
        $data = Order::whereIn('status', ['completed', 'shipped'])
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date')
            ->toArray();

        $days = range(1, now()->daysInMonth);
        $revenue = [];
        $labels = [];

        foreach ($days as $day) {
            $date = now()->year.'-'.str_pad(now()->month, 2, '0', STR_PAD_LEFT).'-'.str_pad($day, 2, '0', STR_PAD_LEFT);
            $labels[] = $day;
            $revenue[] = $data[$date] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan',
                    'data' => $revenue,
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#36A2EB',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
