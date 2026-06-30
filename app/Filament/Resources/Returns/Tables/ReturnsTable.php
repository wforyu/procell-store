<?php

namespace App\Filament\Resources\Returns\Tables;

use App\Models\Returns;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ReturnsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('return_number')
                    ->label('No. Retur')
                    ->searchable(),
                TextColumn::make('order.order_number')
                    ->label('Pesanan')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('reason')
                    ->label('Alasan')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'defective' => 'Produk Cacat',
                        'wrong_item' => 'Tidak Sesuai',
                        'not_as_described' => 'Tidak Sesuai Deskripsi',
                        'damaged' => 'Rusak Saat Kirim',
                        'other' => 'Lainnya',
                        default => $state,
                    }),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Menunggu',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        default => $state,
                    }),
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('Setujui')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->form([
                        Textarea::make('admin_note')
                            ->label('Catatan (opsional)'),
                    ])
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Retur')
                    ->modalDescription('Retur akan disetujui. Apakah Anda yakin?')
                    ->action(function (array $data, Returns $record) {
                        $record->update([
                            'status' => 'approved',
                            'admin_note' => $data['admin_note'] ?? null,
                            'reviewed_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Retur Disetujui')
                            ->body('Retur #'.$record->return_number.' pada pesanan #'.$record->order->order_number.' telah disetujui.')
                            ->success()
                            ->sendToDatabase($record->user);
                    })
                    ->visible(fn (Returns $record) => $record->status === 'pending'),
                Action::make('reject')
                    ->label('Tolak')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->form([
                        Textarea::make('admin_note')
                            ->label('Alasan Penolakan')
                            ->required(),
                    ])
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Retur')
                    ->modalDescription('Apakah Anda yakin ingin menolak retur ini?')
                    ->action(function (array $data, Returns $record) {
                        $record->update([
                            'status' => 'rejected',
                            'admin_note' => $data['admin_note'] ?? null,
                            'reviewed_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Retur Ditolak')
                            ->body('Retur #'.$record->return_number.' pada pesanan #'.$record->order->order_number.' ditolak dengan alasan: '.($data['admin_note'] ?? ''))
                            ->danger()
                            ->sendToDatabase($record->user);
                    })
                    ->visible(fn (Returns $record) => $record->status === 'pending'),
                Action::make('view_images')
                    ->label('Lihat Foto')
                    ->color('gray')
                    ->icon('heroicon-o-photo')
                    ->modalHeading('Foto Bukti Retur')
                    ->modalContent(function (Returns $record) {
                        $images = $record->images;
                        if ($images->isEmpty()) {
                            return view('filament.returns.no-images');
                        }

                        return view('filament.returns.images', compact('images'));
                    })
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
