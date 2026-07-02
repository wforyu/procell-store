<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;

class LoyalCustomersTableWidget extends BaseWidget
{
    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Customer::select('customers.*', DB::raw('COALESCE(customer_orders.total_spent, 0) as total_spent'), DB::raw('COALESCE(customer_orders.order_count, 0) as order_count'))
                    ->join(DB::raw('(SELECT customer_id, SUM(total_amount + shipping_cost - discount_amount - COALESCE(points_discount, 0)) as total_spent, COUNT(*) as order_count FROM orders WHERE status IN (\'completed\', \'shipped\') GROUP BY customer_id) as customer_orders'), 'customers.id', '=', 'customer_orders.customer_id')
                    ->orderByDesc('total_spent')
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('rank')
                    ->label('#')
                    ->rowIndex(),
                TextColumn::make('name')
                    ->label('Pelanggan')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email'),
                TextColumn::make('phone')
                    ->label('Telepon'),
                TextColumn::make('order_count')
                    ->label('Pesanan')
                    ->badge()
                    ->color('primary'),
                TextColumn::make('total_spent')
                    ->label('Total Belanja')
                    ->money('IDR')
                    ->sortable(),
            ])
            ->heading('Pelanggan Setia (Top 10)')
            ->paginated(false)
            ->defaultSort('total_spent', 'desc');
    }
}
