<?php

namespace App\Filament\Resources\BankAccounts\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class BankAccountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('bank_name')
                    ->label('Nama Bank')
                    ->helperText('Nama bank, contoh: Bank Mandiri, Bank BCA, Bank BRI')
                    ->required(),
                TextInput::make('account_number')
                    ->label('No. Rekening')
                    ->helperText('Nomor rekening bank yang akan ditampilkan ke pelanggan')
                    ->required(),
                TextInput::make('account_holder')
                    ->label('Atas Nama')
                    ->helperText('Nama pemilik rekening yang terdaftar di bank')
                    ->required(),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->helperText('Nonaktifkan untuk menyembunyikan rekening dari halaman checkout')
                    ->default(true),
                TextInput::make('sort')
                    ->label('Urutan')
                    ->helperText('Prioritas tampilan (semakin kecil semakin atas)')
                    ->numeric()
                    ->default(0),
            ]);
    }
}
