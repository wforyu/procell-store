<?php

namespace App\Filament\Resources\Coupons\Tables;

use App\Models\Coupon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CouponsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Kode')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Kode kupon disalin')
                    ->weight('bold'),
                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'fixed' => 'info',
                        'percentage' => 'warning',
                    })
                    ->formatStateUsing(fn (string $state): string => $state === 'fixed' ? 'Fixed' : 'Persen'),
                TextColumn::make('value')
                    ->label('Nilai')
                    ->formatStateUsing(function ($state, Coupon $record): string {
                        if ($record->type === 'percentage') {
                            return number_format($state, 0).'%';
                        }

                        return 'Rp '.number_format($state, 0, ',', '.');
                    })
                    ->sortable(),
                TextColumn::make('min_order')
                    ->label('Min. Belanja')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('expires_at')
                    ->label('Berakhir')
                    ->date('d M Y')
                    ->placeholder('Tidak ada')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                TextColumn::make('used_count')
                    ->label('Pemakaian')
                    ->formatStateUsing(fn (Coupon $record): string => $record->used_count.'/'.($record->usage_limit ?: '∞')),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
