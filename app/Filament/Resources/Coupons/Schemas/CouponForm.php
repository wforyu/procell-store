<?php

namespace App\Filament\Resources\Coupons\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;

class CouponForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('Kode Kupon')
                    ->helperText('Kode unik kupon yang akan dimasukkan pelanggan saat checkout')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(50),
                Select::make('type')
                    ->label('Tipe Diskon')
                    ->helperText('Jenis diskon: nominal tetap (Fixed) atau persentase (Percentage)')
                    ->options([
                        'fixed' => 'Fixed (Nominal Tetap)',
                        'percentage' => 'Percentage (Persentase)',
                    ])
                    ->required()
                    ->default('fixed'),
                TextInput::make('value')
                    ->label('Nilai Diskon')
                    ->helperText('Nilai diskon (dalam Rupiah jika Fixed, dalam persen jika Percentage)')
                    ->required()
                    ->default('0')
                    ->prefix('Rp')
                    ->mask(RawJs::make('(function(){var d=$input.replace(/\D/g,\'\');if(!d)return\'\';var p=[],r=d.length;while(r>0){var t=r>3?3:r;p.unshift(\'9\'.repeat(t));r-=t}return p.join(\'.\')})()'))
                    ->mutateDehydratedStateUsing(fn ($state) => $state ? (int) str_replace('.', '', $state) : 0)
                    ->afterStateHydrated(fn (TextInput $component, $state) => $component->state($state ? number_format((int) $state, 0, ',', '.') : '0')),
                TextInput::make('min_order')
                    ->label('Min. Belanja')
                    ->helperText('Minimal belanja agar kupon bisa digunakan (0 = tanpa minimal)')
                    ->required()
                    ->default('0')
                    ->prefix('Rp')
                    ->mask(RawJs::make('(function(){var d=$input.replace(/\D/g,\'\');if(!d)return\'\';var p=[],r=d.length;while(r>0){var t=r>3?3:r;p.unshift(\'9\'.repeat(t));r-=t}return p.join(\'.\')})()'))
                    ->mutateDehydratedStateUsing(fn ($state) => $state ? (int) str_replace('.', '', $state) : 0)
                    ->afterStateHydrated(fn (TextInput $component, $state) => $component->state($state ? number_format((int) $state, 0, ',', '.') : '0')),
                TextInput::make('max_discount')
                    ->label('Maks. Diskon')
                    ->helperText('Maksimal diskon untuk tipe Percentage (biarkan kosong jika tanpa batas)')
                    ->nullable()
                    ->default(null)
                    ->prefix('Rp')
                    ->mask(RawJs::make('(function(){var d=$input.replace(/\D/g,\'\');if(!d)return\'\';var p=[],r=d.length;while(r>0){var t=r>3?3:r;p.unshift(\'9\'.repeat(t));r-=t}return p.join(\'.\')})()'))
                    ->mutateDehydratedStateUsing(fn ($state) => $state ? (int) str_replace('.', '', $state) : null)
                    ->afterStateHydrated(fn (TextInput $component, $state) => $component->state($state ? number_format((int) $state, 0, ',', '.') : null)),
                DateTimePicker::make('starts_at')
                    ->label('Mulai Berlaku')
                    ->helperText('Tanggal mulai berlaku kupon')
                    ->nullable()
                    ->default(null),
                DateTimePicker::make('expires_at')
                    ->label('Berakhir')
                    ->helperText('Tanggal berakhir kupon')
                    ->nullable()
                    ->default(null),
                TextInput::make('usage_limit')
                    ->label('Batas Pemakaian')
                    ->helperText('Batas pemakaian total (0 = tidak terbatas)')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->helperText('Nonaktifkan untuk menyembunyikan kupon sementara')
                    ->required(),
            ]);
    }
}
