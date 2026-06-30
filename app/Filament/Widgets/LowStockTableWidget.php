<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LowStockTableWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::whereColumn('stock', '<=', 'min_stock')
                    ->where('is_active', true)
                    ->orderBy('stock')
            )
            ->columns([
                TextColumn::make('name')
                    ->label('Produk')
                    ->searchable(),
                TextColumn::make('category.name')
                    ->label('Kategori'),
                TextColumn::make('brand')
                    ->label('Merk'),
                TextColumn::make('sku')
                    ->label('SKU'),
                TextColumn::make('stock')
                    ->label('Stok')
                    ->color(fn (int $state): string => match (true) {
                        $state <= 0 => 'danger',
                        default => 'warning',
                    })
                    ->badge(),
                TextColumn::make('min_stock')
                    ->label('Min. Stok'),
            ])
            ->heading('Produk dengan Stok Menipis')
            ->paginated(false)
            ->defaultSort('stock', 'asc');
    }
}
