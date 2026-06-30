<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Support\Icons\Heroicon;

class Dashboard extends \Filament\Pages\Dashboard
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBarSquare;

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?int $navigationSort = 0;

    public static function getNavigationItems(array $urlParameters = []): array
    {
        return array_map(fn ($item) => $item->extraAttributes(['title' => 'Ringkasan penjualan dan statistik toko']), parent::getNavigationItems($urlParameters));
    }
}
