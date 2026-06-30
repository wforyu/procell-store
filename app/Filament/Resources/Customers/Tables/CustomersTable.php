<?php

namespace App\Filament\Resources\Customers\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CustomersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->icon('heroicon-m-user')
                    ->color('primary'),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->icon('heroicon-m-envelope'),
                TextColumn::make('phone')
                    ->label('Telepon')
                    ->searchable()
                    ->icon('heroicon-m-phone'),
                TextColumn::make('orders_count')
                    ->label('Pesanan')
                    ->counts('orders')
                    ->sortable()
                    ->color(fn (int $state): string => match (true) {
                        $state >= 10 => 'success',
                        $state >= 3 => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('total_spent')
                    ->label('Total Belanja')
                    ->money('IDR')
                    ->sortable()
                    ->color('success'),
                TextColumn::make('last_order_date')
                    ->label('Pesanan Terakhir')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('created_at')
                    ->label('Bergabung')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Detail'),
            ])
            ->toolbarActions([])
            ->defaultSort('created_at', 'desc');
    }
}
