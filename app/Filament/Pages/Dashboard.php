<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\LowStockTableWidget;
use App\Filament\Widgets\RevenueChartWidget;
use App\Filament\Widgets\StatsOverviewWidget;
use App\Filament\Widgets\StockMovementChartWidget;
use BackedEnum;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Livewire;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\WidgetConfiguration;
use Illuminate\Support\Collection;

class Dashboard extends \Filament\Pages\Dashboard
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBarSquare;

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?int $navigationSort = 0;

    public static function getNavigationItems(array $urlParameters = []): array
    {
        return array_map(fn ($item) => $item->extraAttributes(['title' => 'Ringkasan penjualan dan statistik toko']), parent::getNavigationItems($urlParameters));
    }

    public function getWidgets(): array
    {
        return [
            StatsOverviewWidget::class,
            RevenueChartWidget::class,
            StockMovementChartWidget::class,
            LowStockTableWidget::class,
        ];
    }

    public function getWidgetsContentComponent(): Component
    {
        return Grid::make($this->getColumns())
            ->schema(fn (): array => $this->buildWidgetsSchema());
    }

    public function buildWidgetsSchema(): array
    {
        return Collection::make($this->getWidgets())
            ->values()
            ->filter(fn ($widget): bool => $this->normalizeWidgetClass($widget)::canView())
            ->map(function ($widget, int $key): Component {
                $widgetClass = $this->normalizeWidgetClass($widget);

                $widgetData = $this->getWidgetData();

                $properties = ($widget instanceof WidgetConfiguration)
                    ? [...$widget->widget::getDefaultProperties(), ...$widget->getProperties()]
                    : $widgetClass::getDefaultProperties();

                if (property_exists($this, 'filters')) {
                    $properties['pageFilters'] = $this->filters;
                }

                $ref = new \ReflectionClass($widgetClass);
                $columnSpan = $ref->getProperty('columnSpan')->getDefaultValue();

                return Livewire::make(
                    $widgetClass,
                    fn (): array => [...$widgetData, ...$properties],
                )
                    ->key("{$widgetClass}-{$key}")
                    ->columnSpan($columnSpan);
            })
            ->all();
    }
}
