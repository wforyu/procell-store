<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ManageSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected string $view = 'filament.pages.manage-settings';

    protected static ?string $navigationLabel = 'Pengaturan';

    protected static ?int $navigationSort = 99;

    protected static ?string $title = 'Pengaturan Toko';

    public static function getNavigationGroup(): string
    {
        return 'Pengaturan';
    }

    public static function getNavigationItems(array $urlParameters = []): array
    {
        return array_map(fn ($item) => $item->extraAttributes(['title' => 'Atur informasi toko, kontak, jam operasional, dan footer']), parent::getNavigationItems($urlParameters));
    }

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'store_name' => Setting::getValue('store_name'),
            'store_description' => Setting::getValue('store_description'),
            'store_email' => Setting::getValue('store_email'),
            'store_phone' => Setting::getValue('store_phone'),
            'store_address' => Setting::getValue('store_address'),
            'store_hours' => Setting::getValue('store_hours', 'Sen-Sab 08:00 - 17:00'),
            'store_is_closed' => Setting::getValue('store_is_closed', 'false') === 'true',
            'store_closed_message' => Setting::getValue('store_closed_message', 'Toko sedang libur'),
            'flash_sale_text' => Setting::getValue('flash_sale_text', 'Flash Sale Akhir Pekan!'),
            'meta_description' => Setting::getValue('meta_description'),
            'meta_keywords' => Setting::getValue('meta_keywords'),
            'footer_text' => Setting::getValue('footer_text'),
            'footer_description' => Setting::getValue('footer_description'),
            'whatsapp_number' => Setting::getValue('whatsapp_number'),
        ]);
    }

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Section::make('Informasi Toko')
                    ->description('Data dasar toko Anda')
                    ->schema([
                        TextInput::make('store_name')->label('Nama Toko')->required(),
                        Textarea::make('store_description')->label('Deskripsi Toko'),
                        TextInput::make('store_email')->label('Email Toko')->email(),
                        TextInput::make('store_phone')->label('Telepon Toko')->helperText('Nomor yang tampil di header website'),
                        Textarea::make('store_address')->label('Alamat Toko'),
                    ])->columns(2),

                Section::make('Jam Operasional')
                    ->description('Atur jam buka toko dan status libur')
                    ->schema([
                        TextInput::make('store_hours')->label('Jam Operasional')->helperText('Contoh: Sen-Sab 08:00 - 17:00 atau Sen-Min 09:00 - 21:00'),
                        Toggle::make('store_is_closed')->label('Toko Sedang Libur / Tutup')->helperText('Jika diaktifkan, jam operasional akan diganti dengan pesan libur'),
                        Textarea::make('store_closed_message')->label('Pesan Libur')->helperText('Pesan yang muncul saat toko libur. Contoh: "Toko sedang libur, kembali buka tanggal 2 Januari 2026"'),
                    ])->columns(2),

                Section::make('Header Promo')
                    ->description('Teks promosi di bagian atas website')
                    ->schema([
                        TextInput::make('flash_sale_text')->label('Teks Flash Sale / Promo')->helperText('Muncul di header samping jam operasional. Biarkan kosong untuk menyembunyikan'),
                    ]),

                Section::make('SEO')
                    ->description('Pengaturan optimasi mesin pencari')
                    ->schema([
                        Textarea::make('meta_description')->label('Meta Deskripsi (SEO)')->helperText('Deskripsi yang muncul di hasil pencarian Google'),
                        TextInput::make('meta_keywords')->label('Meta Keywords (SEO)')->helperText('Kata kunci pisahkan dengan koma'),
                    ]),

                Section::make('Footer')
                    ->schema([
                        TextInput::make('footer_text')->label('Teks Footer'),
                        Textarea::make('footer_description')->label('Deskripsi Footer'),
                    ])->columns(2),

                Section::make('Kontak')
                    ->schema([
                        TextInput::make('whatsapp_number')->label('Nomor WhatsApp')->helperText('Format: 628xxx tanpa tanda + atau spasi'),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            if ($key === 'store_is_closed') {
                $value = $value ? 'true' : 'false';
            }
            Setting::setValue($key, $value);
        }

        Notification::make()
            ->title('Pengaturan berhasil disimpan')
            ->success()
            ->send();
    }
}
