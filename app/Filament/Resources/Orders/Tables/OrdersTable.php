<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Models\Order;
use App\Notifications\OrderStatusChanged;
use App\Services\FonnteService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        $courierOptions = [
            'jne' => 'JNE',
            'jnt' => 'J&T',
            'sicepat' => 'SiCepat',
            'ninja' => 'Ninja Express',
        ];

        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->label('No. Pesanan')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'waiting_confirmation' => 'warning',
                        'processing' => 'info',
                        'shipped' => 'primary',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Menunggu Bayar',
                        'waiting_confirmation' => 'Menunggu Konfirmasi',
                        'processing' => 'Diproses',
                        'shipped' => 'Dikirim',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                        default => $state,
                    }),
                TextColumn::make('payment_method')
                    ->label('Pembayaran')
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'bank_transfer' => 'Transfer',
                        default => '-',
                    })
                    ->toggleable(),
                ImageColumn::make('payment_proof')
                    ->label('Bukti Transfer')
                    ->disk('public')
                    ->visibility('public')
                    ->square()
                    ->extraImgAttributes(['class' => 'object-cover', 'loading' => 'lazy'])
                    ->toggleable(),
                TextColumn::make('courier')
                    ->label('Kurir')
                    ->formatStateUsing(fn (?string $state): string => $courierOptions[$state] ?? '-')
                    ->toggleable(),
                TextColumn::make('tracking_number')
                    ->label('No. Resi')
                    ->toggleable(),
                TextColumn::make('shipping_cost')
                    ->label('Ongkir')
                    ->money('IDR')
                    ->toggleable(),
                TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'pending' => 'Menunggu Bayar',
                        'waiting_confirmation' => 'Menunggu Konfirmasi',
                        'processing' => 'Diproses',
                        'shipped' => 'Dikirim',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ]),
            ])
            ->recordActions([
                Action::make('confirm_payment')
                    ->label('Konfirmasi Bayar')
                    ->color('success')
                    ->icon('heroicon-o-check-badge')
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Pembayaran')
                    ->modalContent(fn (Order $record) => $record->payment_proof
                        ? view('filament.orders.payment-proof-modal', ['imageUrl' => asset('storage/'.$record->payment_proof)])
                        : null
                    )
                    ->action(function (Order $record) {
                        $record->update([
                            'status' => 'processing',
                            'payment_verified_at' => now(),
                        ]);
                        $record->user->notify(new OrderStatusChanged($record, 'waiting_confirmation', 'processing'));
                        app(FonnteService::class)->sendOrderStatus($record, 'waiting_confirmation', 'processing');
                    })
                    ->visible(fn (Order $record) => $record->status === 'waiting_confirmation'),
                Action::make('process')
                    ->label('Proses')
                    ->color('warning')
                    ->icon('heroicon-o-arrow-path')
                    ->requiresConfirmation()
                    ->action(function (Order $record) {
                        $record->update(['status' => 'processing']);
                        $record->user->notify(new OrderStatusChanged($record, 'pending', 'processing'));
                        app(FonnteService::class)->sendOrderStatus($record, 'pending', 'processing');
                    })
                    ->visible(fn (Order $record) => $record->status === 'pending'),
                Action::make('ship')
                    ->label('Kirim')
                    ->color('info')
                    ->icon('heroicon-o-truck')
                    ->form([
                        Select::make('courier')
                            ->label('Kurir')
                            ->options($courierOptions)
                            ->required(),
                        TextInput::make('courier_service')
                            ->label('Layanan')
                            ->required(),
                        TextInput::make('tracking_number')
                            ->label('No. Resi')
                            ->required(),
                    ])
                    ->action(function (array $data, Order $record) {
                        $record->update([
                            'status' => 'shipped',
                            'courier' => $data['courier'],
                            'courier_service' => $data['courier_service'],
                            'tracking_number' => $data['tracking_number'],
                            'shipped_at' => now(),
                        ]);
                        $record->user->notify(new OrderStatusChanged($record, 'processing', 'shipped'));
                        app(FonnteService::class)->sendOrderStatus($record, 'processing', 'shipped');
                    })
                    ->visible(fn (Order $record) => $record->status === 'processing'),
                Action::make('complete')
                    ->label('Selesai')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->requiresConfirmation()
                    ->action(function (Order $record) {
                        $record->update(['status' => 'completed']);
                        $record->user->notify(new OrderStatusChanged($record, 'shipped', 'completed'));
                        app(FonnteService::class)->sendOrderStatus($record, 'shipped', 'completed');
                    })
                    ->visible(fn (Order $record) => $record->status === 'shipped'),
                Action::make('cancel')
                    ->label('Batalkan')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->requiresConfirmation()
                    ->action(function (Order $record) {
                        $oldStatus = $record->status;
                        $record->update(['status' => 'cancelled']);
                        $record->user->notify(new OrderStatusChanged($record, $oldStatus, 'cancelled'));
                        app(FonnteService::class)->sendOrderStatus($record, $oldStatus, 'cancelled');
                    })
                    ->visible(fn (Order $record) => ! in_array($record->status, ['completed', 'cancelled'])),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
                Action::make('export')
                    ->label('Ekspor CSV')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn (): string => route('admin.export.orders.csv')),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
