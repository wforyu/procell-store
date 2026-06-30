<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->helperText('Pilih kategori produk yang sesuai')
                    ->required(),
                FileUpload::make('image')
                    ->label('Gambar Utama')
                    ->image()
                    ->disk('public')
                    ->directory('products')
                    ->helperText('Gambar utama produk yang tampil di katalog dan detail')
                    ->nullable(),
                Repeater::make('images')
                    ->label('Gambar Tambahan')
                    ->relationship('images')
                    ->schema([
                        FileUpload::make('image')
                            ->label('Gambar')
                            ->disk('public')
                            ->directory('products')
                            ->image()
                            ->required(),
                        Toggle::make('is_primary')
                            ->label('Utama'),
                        TextInput::make('sort_order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(3)
                    ->defaultItems(0)
                    ->columnSpanFull()
                    ->addActionLabel('+ Tambah Gambar'),
                TextInput::make('name')
                    ->label('Nama')
                    ->helperText('Nama lengkap produk, akan otomatis membuat slug')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                TextInput::make('brand')
                    ->label('Merk')
                    ->helperText('Merek HP yang kompatibel, contoh: Samsung, iPhone, Xiaomi')
                    ->required()
                    ->placeholder('Contoh: Samsung, iPhone, Xiaomi'),
                TextInput::make('slug')
                    ->label('Slug')
                    ->helperText('URL unik untuk halaman produk (otomatis dari nama)')
                    ->required(),
                TextInput::make('sku')
                    ->label('SKU')
                    ->helperText('Kode unik produk untuk keperluan inventaris')
                    ->required(),
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->helperText('Deskripsi lengkap produk yang akan ditampilkan di halaman detail')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('specifications')
                    ->label('Spesifikasi')
                    ->helperText('Spesifikasi teknis produk (opsional)')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('buying_price')
                    ->label('Harga Beli')
                    ->helperText('Harga modal/pembelian dari supplier')
                    ->required()
                    ->default('0')
                    ->prefix('Rp')
                    ->mask(RawJs::make('(function(){var d=$input.replace(/\D/g,\'\');if(!d)return\'\';var p=[],r=d.length;while(r>0){var t=r>3?3:r;p.unshift(\'9\'.repeat(t));r-=t}return p.join(\'.\')})()'))
                    ->mutateDehydratedStateUsing(fn ($state) => $state ? (int) str_replace('.', '', $state) : 0)
                    ->afterStateHydrated(fn (TextInput $component, $state) => $component->state($state ? number_format((int) $state, 0, ',', '.') : '0')),
                TextInput::make('selling_price')
                    ->label('Harga Jual')
                    ->helperText('Harga jual ke pelanggan')
                    ->required()
                    ->default('0')
                    ->prefix('Rp')
                    ->mask(RawJs::make('(function(){var d=$input.replace(/\D/g,\'\');if(!d)return\'\';var p=[],r=d.length;while(r>0){var t=r>3?3:r;p.unshift(\'9\'.repeat(t));r-=t}return p.join(\'.\')})()'))
                    ->mutateDehydratedStateUsing(fn ($state) => $state ? (int) str_replace('.', '', $state) : 0)
                    ->afterStateHydrated(fn (TextInput $component, $state) => $component->state($state ? number_format((int) $state, 0, ',', '.') : '0')),
                TextInput::make('promo_price')
                    ->label('Harga Promo')
                    ->helperText('Harga diskon/promo — jika diisi, harga ini akan ditampilkan dengan coretan harga normal di toko')
                    ->default(null)
                    ->prefix('Rp')
                    ->mask(RawJs::make('(function(){var d=$input.replace(/\D/g,\'\');if(!d)return\'\';var p=[],r=d.length;while(r>0){var t=r>3?3:r;p.unshift(\'9\'.repeat(t));r-=t}return p.join(\'.\')})()'))
                    ->mutateDehydratedStateUsing(fn ($state) => $state ? (int) str_replace('.', '', $state) : null)
                    ->afterStateHydrated(fn (TextInput $component, $state) => $component->state($state ? number_format((int) $state, 0, ',', '.') : null)),
                TextInput::make('stock')
                    ->label('Stok')
                    ->helperText('Jumlah stok tersedia saat ini')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('min_stock')
                    ->label('Min. Stok')
                    ->helperText('Batas minimal stok — akan muncul notifikasi jika stok di bawah ini')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('unit')
                    ->label('Satuan')
                    ->helperText('Satuan produk, contoh: pcs, unit, set')
                    ->required()
                    ->default('pcs'),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->helperText('Nonaktifkan untuk menyembunyikan produk dari toko')
                    ->required(),
            ]);
    }
}
