<?php

namespace App\Filament\Widgets;

use App\Models\StockMovement;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class StockMovementChartWidget extends ChartWidget
{
    public ?string $heading = 'Aktivitas Stok (7 Hari Terakhir)';

    protected function getData(): array
    {
        $movements = StockMovement::where('created_at', '>=', now()->subDays(7))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('type'), DB::raw('SUM(quantity) as total'))
            ->groupBy('date', 'type')
            ->orderBy('date')
            ->get();

        $dates = collect();
        for ($i = 6; $i >= 0; $i--) {
            $dates->push(now()->subDays($i)->format('Y-m-d'));
        }

        $masuk = [];
        $keluar = [];

        foreach ($dates as $date) {
            $masuk[] = $movements->where('date', $date)->where('type', 'in')->sum('total');
            $keluar[] = $movements->where('date', $date)->where('type', 'out')->sum('total');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Barang Masuk',
                    'data' => $masuk,
                    'backgroundColor' => '#4CAF50',
                    'borderColor' => '#4CAF50',
                ],
                [
                    'label' => 'Barang Keluar',
                    'data' => $keluar,
                    'backgroundColor' => '#F44336',
                    'borderColor' => '#F44336',
                ],
            ],
            'labels' => $dates->map(fn ($d) => Carbon::parse($d)->format('d/m'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
