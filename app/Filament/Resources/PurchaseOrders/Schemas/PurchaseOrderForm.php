<?php

namespace App\Filament\Resources\PurchaseOrders\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;

class PurchaseOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('order_number')
                    ->label('No. PO')
                    ->helperText('Nomor unik purchase order untuk identifikasi')
                    ->required(),
                Select::make('supplier_id')
                    ->label('Supplier')
                    ->helperText('Pilih supplier yang akan dipesan')
                    ->relationship('supplier', 'name')
                    ->required()
                    ->searchable(),
                Select::make('status')
                    ->label('Status')
                    ->helperText('Status purchase order saat ini')
                    ->options([
                        'draft' => 'Draft',
                        'ordered' => 'Dipesan',
                        'partially_received' => 'Sebagian Diterima',
                        'received' => 'Diterima',
                        'cancelled' => 'Dibatalkan',
                    ])
                    ->default('draft')
                    ->required(),
                TextInput::make('total_amount')
                    ->label('Total')
                    ->helperText('Total nilai purchase order (dihitung dari subtotal item)')
                    ->required()
                    ->default('0')
                    ->prefix('Rp')
                    ->disabled()
                    ->afterStateHydrated(fn (TextInput $component, $state) => $component->state($state ? number_format((int) $state, 0, ',', '.') : '0')),
                Textarea::make('notes')
                    ->label('Catatan')
                    ->helperText('Catatan tambahan untuk purchase order ini')
                    ->default(null)
                    ->columnSpanFull(),
                DateTimePicker::make('ordered_at')
                    ->label('Dipesan Pada')
                    ->helperText('Saat PO dikirim ke supplier'),
                DateTimePicker::make('received_at')
                    ->label('Diterima Pada')
                    ->helperText('Saat barang dari PO diterima'),

                Repeater::make('items')
                    ->label('Item Pesanan')
                    ->helperText('Daftar produk yang dipesan beserta harga dan jumlah')
                    ->relationship('items')
                    ->schema([
                        Select::make('product_id')
                            ->label('Produk')
                            ->relationship('product', 'name')
                            ->searchable()
                            ->required()
                            ->columnSpan(3)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('unit_price', 0)),
                        TextInput::make('quantity_ordered')
                            ->label('Jumlah')
                            ->numeric()
                            ->minValue(1)
                            ->required()
                            ->default(1)
                            ->columnSpan(1),
                        TextInput::make('quantity_received')
                            ->label('Diterima')
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->columnSpan(1)
                            ->disabled(fn ($livewire): bool => $livewire instanceof CreateRecord),
                        TextInput::make('unit_price')
                            ->label('Harga Satuan')
                            ->required()
                            ->default('0')
                            ->prefix('Rp')
                            ->columnSpan(1)
                            ->mask(RawJs::make('(function(){var d=$input.replace(/\D/g,\'\');if(!d)return\'\';var p=[],r=d.length;while(r>0){var t=r>3?3:r;p.unshift(\'9\'.repeat(t));r-=t}return p.join(\'.\')})()'))
                            ->mutateDehydratedStateUsing(fn ($state) => $state ? (int) str_replace('.', '', $state) : 0)
                            ->afterStateUpdated(fn ($state, callable $set, callable $get) => $set(
                                'subtotal',
                                (int) str_replace('.', '', $state) * (int) ($get('quantity_ordered') ?? 1)
                            )),
                        TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->prefix('Rp')
                            ->disabled()
                            ->columnSpan(1)
                            ->default('0')
                            ->afterStateHydrated(fn (TextInput $component, $state) => $component->state($state ? number_format((int) $state, 0, ',', '.') : '0')),
                    ])
                    ->columns(7)
                    ->columnSpanFull()
                    ->addable(true)
                    ->deletable(true)
                    ->reorderable(false)
                    ->defaultItems(0)
                    ->mutateRelationshipDataBeforeSaveUsing(function (array $data): array {
                        $unitPrice = $data['unit_price'] ? (int) str_replace('.', '', $data['unit_price']) : 0;
                        $data['unit_price'] = $unitPrice;
                        $data['subtotal'] = ($data['quantity_ordered'] ?? 0) * $unitPrice;

                        return $data;
                    }),
            ]);
    }
}
