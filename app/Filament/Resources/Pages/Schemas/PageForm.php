<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Judul Halaman')
                    ->helperText('Contoh: Cara Order, Syarat & Ketentuan, Kebijakan Privasi')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, $set) => $set('slug', Str::slug($state))),
                TextInput::make('slug')
                    ->label('Slug (URL)')
                    ->helperText('Identifikasi unik untuk URL halaman. Contoh: cara-order')
                    ->required()
                    ->unique(ignoreRecord: true),
                RichEditor::make('content')
                    ->label('Konten')
                    ->helperText('Isi halaman yang akan ditampilkan ke pelanggan. Gunakan toolbar untuk memformat teks.')
                    ->required()
                    ->fileAttachmentsDisk('public')
                    ->fileAttachmentsDirectory('pages'),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->helperText('Nonaktifkan untuk menyembunyikan halaman sementara')
                    ->default(true),
            ]);
    }
}
