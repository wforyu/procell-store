<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama')
                    ->helperText('Nama kategori yang akan ditampilkan di toko')
                    ->required(),
                TextInput::make('slug')
                    ->label('Slug')
                    ->helperText('URL unik untuk halaman kategori (contoh: lcd-display)')
                    ->required(),
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->helperText('Penjelasan singkat tentang kategori ini')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('parent_id')
                    ->label('Induk Kategori')
                    ->helperText('ID kategori induk jika ini sub-kategori (biarkan kosong jika utama)')
                    ->numeric()
                    ->default(null),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->helperText('Nonaktifkan untuk menyembunyikan kategori dari toko')
                    ->required(),
                TextInput::make('sort_order')
                    ->label('Urutan')
                    ->helperText('Urutan tampilan kategori (semakin kecil semakin atas)')
                    ->required()
                    ->numeric()
                    ->default(0),
                FileUpload::make('image')
                    ->label('Gambar')
                    ->disk('public')
                    ->directory('categories')
                    ->helperText('Ikon/gambar kategori (format: JPG/PNG)')
                    ->image(),
            ]);
    }
}
