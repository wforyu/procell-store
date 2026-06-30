<?php

namespace App\Filament\Resources\ProductReviews\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductReviewForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->label('Produk')
                    ->relationship('product', 'name')
                    ->disabled()
                    ->helperText('Produk yang diulas'),
                TextInput::make('user.name')
                    ->label('Pelanggan')
                    ->disabled()
                    ->helperText('Pelanggan yang memberikan ulasan'),
                Select::make('rating')
                    ->label('Rating')
                    ->options([
                        1 => '⭐ (1)',
                        2 => '⭐⭐ (2)',
                        3 => '⭐⭐⭐ (3)',
                        4 => '⭐⭐⭐⭐ (4)',
                        5 => '⭐⭐⭐⭐⭐ (5)',
                    ])
                    ->disabled()
                    ->helperText('Rating bintang 1-5'),
                Textarea::make('review')
                    ->label('Ulasan')
                    ->disabled()
                    ->helperText('Komentar ulasan dari pelanggan')
                    ->columnSpanFull(),
                Toggle::make('is_approved')
                    ->label('Setujui')
                    ->helperText('Setujui untuk menampilkan ulasan di halaman produk'),
            ]);
    }
}
