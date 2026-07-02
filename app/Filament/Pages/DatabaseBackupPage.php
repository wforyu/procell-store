<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class DatabaseBackupPage extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCircleStack;

    protected string $view = 'filament.pages.database-backup';

    protected static ?string $navigationLabel = 'Backup Database';

    protected static ?int $navigationSort = 2;

    protected static ?string $title = 'Backup Database';

    public array $backups = [];

    public function mount(): void
    {
        $this->loadBackups();
    }

    public function loadBackups(): void
    {
        $path = storage_path('app/backups');
        if (! File::exists($path)) {
            File::makeDirectory($path, 0755, true);
            $this->backups = [];

            return;
        }

        $files = File::files($path);
        $this->backups = collect($files)
            ->filter(fn ($file) => $file->getExtension() === 'sql')
            ->map(fn ($file) => [
                'name' => $file->getFilename(),
                'size' => $file->getSize(),
                'size_formatted' => $this->formatBytes($file->getSize()),
                'date' => $file->getMTime(),
                'date_formatted' => date('d/m/Y H:i:s', $file->getMTime()),
            ])
            ->sortByDesc('date')
            ->values()
            ->toArray();
    }

    public function createBackup(): void
    {
        $exitCode = Artisan::call('db:backup');

        if ($exitCode === 0) {
            $filename = trim(Artisan::output());
            Notification::make()->title('Backup berhasil dibuat')->success()->send();
        } else {
            Notification::make()->title('Gagal membuat backup')->danger()->send();
        }

        $this->loadBackups();
    }

    public function deleteBackup(string $filename): void
    {
        $path = storage_path('app/backups/'.$filename);
        if (File::exists($path)) {
            File::delete($path);
            Notification::make()->title('Backup berhasil dihapus')->success()->send();
        }

        $this->loadBackups();
    }

    protected function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        return round($bytes / pow(1024, $pow), $precision).' '.$units[$pow];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('createBackup')
                ->label('Buat Backup Baru')
                ->icon('heroicon-m-plus-circle')
                ->color('primary')
                ->action('createBackup'),
        ];
    }

    public static function getNavigationGroup(): string
    {
        return 'Sistem';
    }
}
