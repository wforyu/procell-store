<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;

class TopProductsTableWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::select('products.*', DB::raw('COALESCE(sold.total_qty, 0) as total_sold'))
                    ->leftJoin(DB::raw('(SELECT product_id, SUM(quantity) as total_qty FROM order_items GROUP BY product_id) as sold'), 'products.id', '=', 'sold.product_id')
                    ->where('is_active', true)
                    ->orderByDesc('total_sold')
                    ->with('category')
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('rank')
                    ->label('#')
                    ->rowIndex(),
                TextColumn::make('name')
                    ->label('Produk')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('category.name')
                    ->label('Kategori'),
                TextColumn::make('brand')
                    ->label('Merk'),
                TextColumn::make('stock')
                    ->label('Stok')
                    ->badge()
                    ->color(fn (int $state) => $state <= 0 ? 'danger' : ($state <= 5 ? 'warning' : 'success')),
                TextColumn::make('total_sold')
                    ->label('Terjual')
                    ->sortable()
                    ->badge()
                    ->color('primary'),
            ])
            ->heading('Produk Terlaris (Top 10)')
            ->paginated(false)
            ->defaultSort('total_sold', 'desc');
    }
}
