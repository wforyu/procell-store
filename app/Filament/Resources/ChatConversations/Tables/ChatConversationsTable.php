<?php

namespace App\Filament\Resources\ChatConversations\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ChatConversationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Pelanggan')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('subject')
                    ->label('Subjek')
                    ->searchable()
                    ->limit(30),
                TextColumn::make('messages_count')
                    ->label('Pesan')
                    ->counts('messages')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state === 'open' ? 'Aktif' : 'Ditutup')
                    ->color(fn ($state) => $state === 'open' ? 'success' : 'gray'),
                TextColumn::make('updated_at')
                    ->label('Terakhir')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'open' => 'Aktif',
                        'closed' => 'Ditutup',
                    ]),
            ]);
    }
}
