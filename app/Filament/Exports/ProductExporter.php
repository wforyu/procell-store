<?php

namespace App\Filament\Exports;

use App\Models\Product;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class ProductExporter extends Exporter
{
    protected static ?string $model = Product::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('name')
                ->label('Nama Produk'),
            ExportColumn::make('category.name')
                ->label('Kategori'),
            ExportColumn::make('brand')
                ->label('Brand'),
            ExportColumn::make('sku')
                ->label('SKU'),
            ExportColumn::make('buying_price')
                ->label('Harga Beli')
                ->formatStateUsing(fn (float $state): string => 'Rp '.number_format($state, 0, ',', '.')),
            ExportColumn::make('selling_price')
                ->label('Harga Jual')
                ->formatStateUsing(fn (float $state): string => 'Rp '.number_format($state, 0, ',', '.')),
            ExportColumn::make('stock')
                ->label('Stok'),
            ExportColumn::make('min_stock')
                ->label('Stok Minimal'),
            ExportColumn::make('unit')
                ->label('Satuan'),
            ExportColumn::make('avg_rating')
                ->label('Rating'),
            ExportColumn::make('review_count')
                ->label('Jumlah Review'),
            ExportColumn::make('is_active')
                ->label('Aktif')
                ->formatStateUsing(fn (bool $state): string => $state ? 'Ya' : 'Tidak'),
            ExportColumn::make('description')
                ->label('Deskripsi'),
            ExportColumn::make('created_at')
                ->label('Dibuat'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Ekspor produk selesai. '.Number::format($export->successful_rows).' baris berhasil diekspor.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' baris gagal diekspor.';
        }

        return $body;
    }
}
