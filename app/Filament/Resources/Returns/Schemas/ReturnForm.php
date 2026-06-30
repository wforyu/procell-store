<?php

namespace App\Filament\Resources\Returns\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ReturnForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('return_number')
                    ->label('No. Retur')
                    ->helperText('Nomor unik pengajuan retur')
                    ->disabled(),
                TextInput::make('order.order_number')
                    ->label('No. Pesanan')
                    ->helperText('Nomor pesanan terkait')
                    ->disabled(),
                TextInput::make('user.name')
                    ->label('Pelanggan')
                    ->helperText('Nama pelanggan yang mengajukan retur')
                    ->disabled(),
                Select::make('status')
                    ->label('Status')
                    ->helperText('Ubah status untuk menyetujui atau menolak retur')
                    ->options([
                        'pending' => 'Menunggu',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                    ])
                    ->required(),
                Select::make('reason')
                    ->label('Alasan')
                    ->helperText('Alasan retur yang dipilih pelanggan')
                    ->options([
                        'defective' => 'Produk Cacat',
                        'wrong_item' => 'Tidak Sesuai',
                        'not_as_described' => 'Tidak Sesuai Deskripsi',
                        'damaged' => 'Rusak Saat Kirim',
                        'other' => 'Lainnya',
                    ])
                    ->disabled(),
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->helperText('Penjelasan lengkap dari pelanggan tentang alasan retur')
                    ->disabled()
                    ->columnSpanFull(),
                Textarea::make('admin_note')
                    ->label('Catatan Admin')
                    ->helperText('Catatan internal atau tanggapan untuk pelanggan (wajib jika menolak)')
                    ->columnSpanFull(),
            ]);
    }
}
