<?php

namespace App\Filament\Resources\Expenses\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;

class ExpenseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('description')
                    ->label('Deskripsi')
                    ->helperText('Nama atau keterangan pengeluaran')
                    ->required(),
                TextInput::make('amount')
                    ->label('Jumlah')
                    ->helperText('Nominal pengeluaran dalam Rupiah')
                    ->required()
                    ->default('0')
                    ->prefix('Rp')
                    ->mask(RawJs::make('(function(){var d=$input.replace(/\D/g,\'\');if(!d)return\'\';var p=[],r=d.length;while(r>0){var t=r>3?3:r;p.unshift(\'9\'.repeat(t));r-=t}return p.join(\'.\')})()'))
                    ->mutateDehydratedStateUsing(fn ($state) => $state ? (int) str_replace('.', '', $state) : 0),
                TextInput::make('category')
                    ->label('Kategori')
                    ->helperText('Jenis pengeluaran, contoh: Operasional, Belanja Stok, Listrik')
                    ->required(),
                DatePicker::make('date')
                    ->label('Tanggal')
                    ->helperText('Tanggal terjadinya pengeluaran')
                    ->required(),
                Textarea::make('notes')
                    ->label('Catatan')
                    ->helperText('Informasi tambahan tentang pengeluaran ini (opsional)')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}
