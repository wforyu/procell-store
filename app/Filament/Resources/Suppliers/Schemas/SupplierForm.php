<?php

namespace App\Filament\Resources\Suppliers\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SupplierForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('name')
                    ->label('Nama Supplier')
                    ->helperText('Nama perusahaan atau perorangan pemasok barang')
                    ->required(),
                TextInput::make('contact_person')
                    ->label('Kontak Person')
                    ->helperText('Nama PIC (person in charge) dari pihak supplier')
                    ->default(null),
                TextInput::make('phone')
                    ->label('Telepon')
                    ->helperText('Nomor telepon/WA yang bisa dihubungi')
                    ->default(null),
                TextInput::make('email')
                    ->label('Email')
                    ->helperText('Alamat email supplier (untuk komunikasi formal)')
                    ->email()
                    ->default(null),
                Textarea::make('address')
                    ->label('Alamat')
                    ->helperText('Alamat lengkap supplier')
                    ->default(null)
                    ->columnSpanFull(),
                Textarea::make('notes')
                    ->label('Catatan')
                    ->helperText('Catatan tambahan mengenai supplier ini')
                    ->default(null)
                    ->columnSpanFull(),
                Select::make('is_active')
                    ->label('Status')
                    ->helperText('Nonaktifkan jika tidak lagi bekerja sama dengan supplier ini')
                    ->options([
                        1 => 'Aktif',
                        0 => 'Nonaktif',
                    ])
                    ->default(1)
                    ->required(),
            ]);
    }
}
