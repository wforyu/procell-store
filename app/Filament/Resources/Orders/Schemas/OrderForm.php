<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('order_number')
                    ->label('No. Pesanan')
                    ->helperText('Nomor unik pesanan — otomatis dibuat saat checkout')
                    ->required()
                    ->columnSpan(1),
                Select::make('status')
                    ->label('Status')
                    ->helperText('Status pesanan saat ini')
                    ->options([
                        'pending' => 'Menunggu Pembayaran',
                        'waiting_confirmation' => 'Menunggu Konfirmasi',
                        'processing' => 'Diproses',
                        'shipped' => 'Dikirim',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ])
                    ->default('pending')
                    ->required()
                    ->columnSpan(1),
                TextInput::make('total_amount')
                    ->label('Total Produk')
                    ->helperText('Total harga seluruh item (belum termasuk ongkir)')
                    ->required()
                    ->numeric()
                    ->default(0.0)
                    ->prefix('Rp')
                    ->columnSpan(1),
                TextInput::make('shipping_cost')
                    ->label('Ongkos Kirim')
                    ->helperText('Biaya pengiriman yang dibebankan ke pelanggan')
                    ->numeric()
                    ->default(0)
                    ->prefix('Rp')
                    ->columnSpan(1),
                Textarea::make('shipping_address')
                    ->label('Alamat Pengiriman')
                    ->helperText('Alamat lengkap tujuan pengiriman pesanan')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('notes')
                    ->label('Catatan')
                    ->helperText('Catatan khusus dari pelanggan (opsional)')
                    ->default(null)
                    ->columnSpanFull(),

                // Payment Section
                TextInput::make('payment_method')
                    ->label('Metode Pembayaran')
                    ->helperText('Metode yang dipilih pelanggan, contoh: bank_transfer')
                    ->default(null)
                    ->columnSpan(1),
                FileUpload::make('payment_proof')
                    ->label('Bukti Pembayaran')
                    ->image()
                    ->disk('public')
                    ->directory('payment-proofs')
                    ->visibility('public')
                    ->imagePreviewHeight('200')
                    ->helperText('File bukti transfer yang diupload pelanggan — klik untuk preview ukuran penuh')
                    ->columnSpan(1),
                DateTimePicker::make('payment_verified_at')
                    ->label('Terverifikasi Pada')
                    ->helperText('Saat admin mengkonfirmasi pembayaran')
                    ->columnSpan(1),

                // Shipping Section
                Select::make('courier')
                    ->label('Kurir')
                    ->helperText('Jasa pengiriman yang digunakan')
                    ->options([
                        'jne' => 'JNE',
                        'jnt' => 'J&T',
                        'sicepat' => 'SiCepat',
                        'ninja' => 'Ninja Express',
                    ])
                    ->default(null)
                    ->columnSpan(1),
                TextInput::make('courier_service')
                    ->label('Layanan')
                    ->helperText('Layanan kurir, contoh: REG, YES, BEST')
                    ->default(null)
                    ->columnSpan(1),
                TextInput::make('tracking_number')
                    ->label('No. Resi')
                    ->helperText('Nomor resi pengiriman untuk tracking')
                    ->default(null)
                    ->columnSpan(1),
                DateTimePicker::make('shipped_at')
                    ->label('Dikirim Pada')
                    ->helperText('Saat pesanan dikirim oleh kurir')
                    ->columnSpan(1),
            ]);
    }
}
