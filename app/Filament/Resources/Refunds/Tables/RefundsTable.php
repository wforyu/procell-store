<?php

namespace App\Filament\Resources\Refunds\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RefundsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('refund_number')
                    ->label('No. Refund')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('order.order_number')
                    ->label('Pesanan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Pelanggan')
                    ->searchable(),
                TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('reason')
                    ->label('Alasan')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'produk_cacat' => 'Produk Cacat',
                        'produk_salah' => 'Tidak Sesuai',
                        'pesanan_dibatalkan' => 'Dibatalkan',
                        'pengiriman_rusak' => 'Rusak Kirim',
                        'customer_menolak' => 'Customer Menolak',
                        'kelebihan_bayar' => 'Kelebihan Bayar',
                        default => $state,
                    })
                    ->badge(),
                TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'pending' => 'Menunggu',
                        'approved' => 'Disetujui',
                        'processed' => 'Diproses',
                        'completed' => 'Selesai',
                        'rejected' => 'Ditolak',
                        default => $state,
                    })
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'info',
                        'processed' => 'primary',
                        'completed' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('method')
                    ->label('Metode')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'bank_transfer' => 'Transfer',
                        'cash' => 'Tunai',
                        'midtrans' => 'Midtrans',
                        default => $state,
                    }),
                TextColumn::make('processor.name')
                    ->label('Diproses Oleh'),
                TextColumn::make('processed_at')
                    ->label('Tgl Proses')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->defaultSort('created_at', 'desc')
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
