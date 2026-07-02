<?php

namespace App\Filament\Pages;

use App\Models\User;
use App\Notifications\BroadcastNotification;
use App\Services\FonnteService;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class BroadcastPage extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMegaphone;

    protected string $view = 'filament.pages.broadcast';

    protected static ?string $navigationLabel = 'Broadcast';

    protected static ?int $navigationSort = 3;

    protected static ?string $title = 'Broadcast Promo & Pengumuman';

    public ?array $data = [];

    public FonnteService $fonnte;

    public function boot(FonnteService $fonnte): void
    {
        $this->fonnte = $fonnte;
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('channel')
                    ->label('Saluran')
                    ->helperText('Pilih media pengiriman')
                    ->options([
                        'email' => 'Email',
                        'whatsapp' => 'WhatsApp',
                        'both' => 'Email & WhatsApp',
                    ])
                    ->required(),
                Select::make('recipients')
                    ->label('Penerima')
                    ->helperText('Pilih target penerima broadcast')
                    ->options([
                        'all_customers' => 'Semua Pelanggan',
                        'active_customers' => 'Pelanggan Aktif (pernah order)',
                        'inactive_customers' => 'Pelanggan Tidak Aktif (belum pernah order)',
                    ])
                    ->required(),
                TextInput::make('subject')
                    ->label('Subjek (Email)')
                    ->helperText('Judul email yang akan dikirim (wajib jika via Email)')
                    ->required(fn ($get) => in_array($get('channel'), ['email', 'both']))
                    ->visible(fn ($get) => in_array($get('channel'), ['email', 'both'])),
                Textarea::make('message')
                    ->label('Pesan')
                    ->helperText('Isi pesan broadcast. Untuk WhatsApp maksimal 1000 karakter.')
                    ->required()
                    ->maxLength(65535)
                    ->rows(8)
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function send(): void
    {
        $data = $this->form->getState();

        $customers = match ($data['recipients']) {
            'all_customers' => User::where('is_admin', false)->get(),
            'active_customers' => User::where('is_admin', false)->whereHas('orders')->get(),
            'inactive_customers' => User::where('is_admin', false)->whereDoesntHave('orders')->get(),
            default => collect(),
        };

        if ($customers->isEmpty()) {
            Notification::make()->title('Tidak ada pelanggan yang sesuai kriteria')->warning()->send();

            return;
        }

        $sentEmail = 0;
        $sentWa = 0;

        foreach ($customers as $customer) {
            if (in_array($data['channel'], ['email', 'both'])) {
                $customer->notify(new BroadcastNotification($data['subject'], $data['message']));
                $sentEmail++;
            }

            if (in_array($data['channel'], ['whatsapp', 'both'])) {
                $phone = $customer->phone ?? $customer->customer?->phone;
                if (! empty($phone)) {
                    $this->fonnte->send($phone, $data['message']);
                    $sentWa++;
                }
            }
        }

        $parts = [];
        if ($sentEmail > 0) {
            $parts[] = "{$sentEmail} Email";
        }
        if ($sentWa > 0) {
            $parts[] = "{$sentWa} WhatsApp";
        }

        Notification::make()
            ->title('Broadcast berhasil dikirim!')
            ->body('Pesan terkirim ke '.implode(' dan ', $parts).' dari total '.$customers->count().' pelanggan.')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    public static function getNavigationGroup(): string
    {
        return 'Sistem';
    }
}
