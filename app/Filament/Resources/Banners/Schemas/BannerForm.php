<?php

namespace App\Filament\Resources\Banners\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class BannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Judul')
                    ->helperText('Judul banner yang muncul di slider halaman utama')
                    ->required(),
                Select::make('type')
                    ->label('Tipe')
                    ->options([
                        'slider' => 'Slider',
                        'popup' => 'Popup',
                    ])
                    ->helperText('Slider = tampil di carousel halaman utama. Popup = muncul sebagai modal saat pengunjung masuk')
                    ->default('slider')
                    ->required(),
                TextInput::make('subtitle')
                    ->label('Subjudul')
                    ->helperText('Teks pendukung di bawah judul (opsional)')
                    ->default(null),
                FileUpload::make('image')
                    ->label('Gambar')
                    ->image()
                    ->disk('public')
                    ->directory('banners')
                    ->helperText('Ukuran ideal: 1200x400px, format JPG/PNG')
                    ->required(),
                TextInput::make('link')
                    ->label('Tautan')
                    ->helperText('URL tujuan saat banner diklik (contoh: /products)')
                    ->default(null),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->helperText('Nonaktifkan untuk menyembunyikan banner sementara')
                    ->required(),
                TextInput::make('sort_order')
                    ->label('Urutan')
                    ->helperText('Urutan tampilan banner di slider (1 = paling kiri/pertama)')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
