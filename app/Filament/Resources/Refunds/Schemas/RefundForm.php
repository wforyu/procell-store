<?php

namespace App\Filament\Resources\Refunds\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;

class RefundForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('order_id')
                    ->label('Pesanan')
                    ->helperText('Pilih pesanan yang akan direfund')
                    ->relationship('order', 'order_number')
                    ->searchable()
                    ->required(),
                TextInput::make('amount')
                    ->label('Jumlah Refund')
                    ->helperText('Nominal uang yang dikembalikan ke pelanggan')
                    ->required()
                    ->prefix('Rp')
                    ->mask(RawJs::make('(function(){var d=$input.replace(/\D/g,\'\');if(!d)return\'\';var p=[],r=d.length;while(r>0){var t=r>3?3:r;p.unshift(\'9\'.repeat(t));r-=t}return p.join(\'.\')})()'))
                    ->mutateDehydratedStateUsing(fn ($state) => $state ? (int) str_replace('.', '', $state) : 0)
                    ->afterStateHydrated(fn (TextInput $component, $state) => $component->state($state ? number_format((int) $state, 0, ',', '.') : '0')),
                Select::make('method')
                    ->label('Metode Refund')
                    ->helperText('Cara pengembalian dana ke pelanggan')
                    ->options([
                        'bank_transfer' => 'Transfer Bank',
                        'cash' => 'Tunai',
                        'midtrans' => 'Midtrans',
                    ])
                    ->required(),
                Select::make('reason')
                    ->label('Alasan Refund')
                    ->helperText('Pilih alasan utama dilakukannya refund')
                    ->options([
                        'produk_cacat' => 'Produk Cacat',
                        'produk_salah' => 'Produk Tidak Sesuai',
                        'pesanan_dibatalkan' => 'Pesanan Dibatalkan',
                        'pengiriman_rusak' => 'Rusak Saat Pengiriman',
                        'customer_menolak' => 'Customer Menolak',
                        'kelebihan_bayar' => 'Kelebihan Pembayaran',
                        'lainnya' => 'Lainnya',
                    ])
                    ->required(),
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->helperText('Penjelasan detail tentang refund ini')
                    ->columnSpanFull(),
                Textarea::make('notes')
                    ->label('Catatan Admin')
                    ->helperText('Catatan internal untuk admin')
                    ->columnSpanFull(),
            ]);
    }
}
