<?php

namespace App\Filament\Resources\ChatConversations\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;

class ChatConversationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('user.name')
                    ->label('Pelanggan')
                    ->helperText('Nama pelanggan yang mengirim pesan')
                    ->disabled(),
                TextInput::make('subject')
                    ->label('Subjek')
                    ->helperText('Topik percakapan')
                    ->disabled(),
                Select::make('status')
                    ->label('Status')
                    ->helperText('Tutup percakapan jika sudah selesai')
                    ->options([
                        'open' => 'Aktif',
                        'closed' => 'Ditutup',
                    ])
                    ->required(),
                TextInput::make('created_at')
                    ->label('Mulai')
                    ->helperText('Tanggal percakapan dimulai')
                    ->disabled(),
                View::make('filament.chat-conversations.messages')
                    ->label('Percakapan')
                    ->columnSpanFull(),
                Textarea::make('admin_reply')
                    ->label('Balas Pesan')
                    ->helperText('Tulis balasan Anda sebagai admin')
                    ->placeholder('Ketik balasan di sini...')
                    ->columnSpanFull(),
            ]);
    }
}
