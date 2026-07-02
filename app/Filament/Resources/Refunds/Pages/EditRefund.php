<?php

namespace App\Filament\Resources\Refunds\Pages;

use App\Filament\Resources\Refunds\RefundResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditRefund extends EditRecord
{
    protected static string $resource = RefundResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('approve')
                ->label('Setujui Refund')
                ->icon('heroicon-m-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn () => $this->record->status === 'pending')
                ->action(fn () => $this->updateStatus('approved')),
            Action::make('process')
                ->label('Proses Refund')
                ->icon('heroicon-m-arrow-path')
                ->color('info')
                ->requiresConfirmation()
                ->visible(fn () => $this->record->status === 'approved')
                ->action(fn () => $this->updateStatus('processed')),
            Action::make('complete')
                ->label('Selesaikan Refund')
                ->icon('heroicon-m-check')
                ->color('primary')
                ->requiresConfirmation()
                ->visible(fn () => $this->record->status === 'processed')
                ->action(fn () => $this->updateStatus('completed')),
            Action::make('reject')
                ->label('Tolak Refund')
                ->icon('heroicon-m-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->visible(fn () => in_array($this->record->status, ['pending', 'approved']))
                ->action(fn () => $this->updateStatus('rejected')),
        ];
    }

    protected function updateStatus(string $status): void
    {
        $this->record->update([
            'status' => $status,
            'processed_by' => auth()->id(),
            'processed_at' => in_array($status, ['completed', 'rejected']) ? now() : $this->record->processed_at,
        ]);

        $this->notify('success', match ($status) {
            'approved' => 'Refund berhasil disetujui',
            'processed' => 'Refund sedang diproses',
            'completed' => 'Refund telah selesai',
            'rejected' => 'Refund telah ditolak',
            default => 'Status refund diperbarui',
        });
    }
}
