<?php

namespace App\Filament\Resources\PurchaseOrders\Tables;

use App\Models\PurchaseOrder;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PurchaseOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->label('No. PO')
                    ->searchable(),
                TextColumn::make('supplier.name')
                    ->label('Supplier')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'ordered' => 'warning',
                        'partially_received' => 'info',
                        'received' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'draft' => 'Draft',
                        'ordered' => 'Dipesan',
                        'partially_received' => 'Sebagian Diterima',
                        'received' => 'Diterima',
                        'cancelled' => 'Dibatalkan',
                        default => $state,
                    }),
                TextColumn::make('items_count')
                    ->label('Jumlah Item')
                    ->counts('items'),
                TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Dibuat Oleh')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('ordered_at')
                    ->label('Dipesan')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('received_at')
                    ->label('Diterima')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'draft' => 'Draft',
                        'ordered' => 'Dipesan',
                        'partially_received' => 'Sebagian Diterima',
                        'received' => 'Diterima',
                        'cancelled' => 'Dibatalkan',
                    ]),
            ])
            ->recordActions([
                // Order action: draft → ordered
                Action::make('order')
                    ->label('Pesan ke Supplier')
                    ->color('warning')
                    ->icon('heroicon-o-shopping-cart')
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Pesan ke Supplier')
                    ->modalDescription('Apakah Anda yakin ingin mengirimkan PO ini ke supplier? Status akan berubah menjadi "Dipesan".')
                    ->action(fn (PurchaseOrder $record) => $record->update([
                        'status' => 'ordered',
                        'ordered_at' => now(),
                        'user_id' => auth()->id(),
                    ]))
                    ->visible(fn (PurchaseOrder $record) => $record->status === 'draft'),
                // Receive action: ordered/partially_received → received
                Action::make('receive')
                    ->label('Terima')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->form(function (PurchaseOrder $record) {
                        return [
                            Repeater::make('items')
                                ->label('Item yang Diterima')
                                ->schema([
                                    TextInput::make('product_name')
                                        ->label('Produk')
                                        ->disabled()
                                        ->default(fn ($state, $get, $component, $record) => $record?->product?->name ?? ''),
                                    TextInput::make('quantity_ordered')
                                        ->label('Dipesan')
                                        ->numeric()
                                        ->disabled(),
                                    TextInput::make('quantity_received')
                                        ->label('Diterima')
                                        ->numeric()
                                        ->minValue(0)
                                        ->required(),
                                ])
                                ->columns(3)
                                ->defaultItems(function () use ($record) {
                                    return $record->items->map(fn ($item) => [
                                        'product_name' => $item->product->name,
                                        'quantity_ordered' => $item->quantity_ordered,
                                        'quantity_received' => $item->quantity_received,
                                        '_item_id' => $item->id,
                                    ])->toArray();
                                })
                                ->addable(false)
                                ->deletable(false)
                                ->reorderable(false),
                        ];
                    })
                    ->action(function (array $data, PurchaseOrder $record) {
                        $allFullyReceived = true;

                        foreach ($data['items'] as $itemData) {
                            $item = $record->items()->find($itemData['_item_id']);

                            if (! $item) {
                                continue;
                            }

                            $previousReceived = $item->quantity_received;
                            $newReceived = (int) $itemData['quantity_received'];

                            if ($newReceived <= $previousReceived) {
                                continue;
                            }

                            $additionalQty = $newReceived - $previousReceived;

                            $item->update([
                                'quantity_received' => $newReceived,
                            ]);

                            // Update product stock
                            $product = $item->product;
                            $stockBefore = $product->stock;
                            $product->increment('stock', $additionalQty);

                            // Create stock movement record
                            $product->stockMovements()->create([
                                'user_id' => auth()->id(),
                                'type' => 'in',
                                'quantity' => $additionalQty,
                                'stock_before' => $stockBefore,
                                'stock_after' => $product->fresh()->stock,
                                'reference_type' => 'purchase_order',
                                'reference_id' => $record->id,
                                'note' => 'Penerimaan dari PO: '.$record->order_number,
                            ]);

                            if ($newReceived < $item->quantity_ordered) {
                                $allFullyReceived = false;
                            }
                        }

                        // Recalculate total_amount from items
                        $total = $record->items()->sum('subtotal');
                        $record->update([
                            'total_amount' => $total,
                        ]);

                        // Update status
                        if ($allFullyReceived) {
                            $record->update([
                                'status' => 'received',
                                'received_at' => now(),
                            ]);
                        } else {
                            $record->update([
                                'status' => 'partially_received',
                            ]);
                        }
                    })
                    ->visible(fn (PurchaseOrder $record) => in_array($record->status, ['ordered', 'partially_received'])),
                // Cancel action
                Action::make('cancel')
                    ->label('Batalkan')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Pembatalan')
                    ->modalDescription('Apakah Anda yakin ingin membatalkan PO ini?')
                    ->action(fn (PurchaseOrder $record) => $record->update([
                        'status' => 'cancelled',
                    ]))
                    ->visible(fn (PurchaseOrder $record) => ! in_array($record->status, ['cancelled', 'received'])),
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
