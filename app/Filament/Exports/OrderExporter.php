<?php

namespace App\Filament\Exports;

use App\Models\Order;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class OrderExporter extends Exporter
{
    protected static ?string $model = Order::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('order_number')
                ->label('No. Pesanan'),
            ExportColumn::make('user.name')
                ->label('Pelanggan'),
            ExportColumn::make('status')
                ->label('Status')
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'pending' => 'Menunggu Bayar',
                    'waiting_confirmation' => 'Menunggu Konfirmasi',
                    'processing' => 'Diproses',
                    'shipped' => 'Dikirim',
                    'completed' => 'Selesai',
                    'cancelled' => 'Dibatalkan',
                    default => $state,
                }),
            ExportColumn::make('total_amount')
                ->label('Total Produk')
                ->formatStateUsing(fn (float $state): string => 'Rp '.number_format($state, 0, ',', '.')),
            ExportColumn::make('shipping_cost')
                ->label('Ongkos Kirim')
                ->formatStateUsing(fn (float $state): string => 'Rp '.number_format($state, 0, ',', '.')),
            ExportColumn::make('discount_amount')
                ->label('Diskon')
                ->formatStateUsing(fn (?float $state): string => $state ? 'Rp '.number_format($state, 0, ',', '.') : '-'),
            ExportColumn::make('shipping_address')
                ->label('Alamat Pengiriman'),
            ExportColumn::make('payment_method')
                ->label('Pembayaran')
                ->formatStateUsing(fn (?string $state): string => match ($state) {
                    'bank_transfer' => 'Transfer Bank',
                    default => $state ?? '-',
                }),
            ExportColumn::make('courier')
                ->label('Kurir')
                ->formatStateUsing(fn (?string $state): string => match ($state) {
                    'jne' => 'JNE', 'jnt' => 'J&T', 'sicepat' => 'SiCepat', 'ninja' => 'Ninja',
                    default => $state ?? '-',
                }),
            ExportColumn::make('courier_service')
                ->label('Layanan'),
            ExportColumn::make('tracking_number')
                ->label('No. Resi'),
            ExportColumn::make('notes')
                ->label('Catatan'),
            ExportColumn::make('payment_verified_at')
                ->label('Terverifikasi'),
            ExportColumn::make('shipped_at')
                ->label('Dikirim Pada'),
            ExportColumn::make('received_at')
                ->label('Diterima Pada'),
            ExportColumn::make('created_at')
                ->label('Dibuat'),
            ExportColumn::make('updated_at')
                ->label('Diperbarui'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Ekspor pesanan selesai. '.Number::format($export->successful_rows).' baris berhasil diekspor.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' baris gagal diekspor.';
        }

        return $body;
    }
}
