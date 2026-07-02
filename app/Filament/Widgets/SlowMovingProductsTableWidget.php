<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;

class SlowMovingProductsTableWidget extends BaseWidget
{
    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Product::select('products.*', DB::raw('COALESCE(sold.quantity, 0) as total_sold'))
                    ->leftJoin(DB::raw('(SELECT product_id, SUM(quantity) as quantity FROM order_items GROUP BY product_id) as sold'), 'products.id', '=', 'sold.product_id')
                    ->where('is_active', true)
                    ->where('stock', '>', 0)
                    ->where(DB::raw('COALESCE(sold.quantity, 0)'), '<', 3)
                    ->with('category')
                    ->orderByDesc('stock')
                    ->limit(10)
            )
            ->columns([
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
                    ->color('warning'),
                TextColumn::make('total_sold')
                    ->label('Terjual')
                    ->badge()
                    ->color('danger'),
                TextColumn::make('selling_price')
                    ->label('Harga')
                    ->money('IDR'),
            ])
            ->heading('Produk Lambat Bergerak (Stok Tinggi, Penjualan Rendah)')
            ->paginated(false)
            ->defaultSort('stock', 'desc');
    }
}
