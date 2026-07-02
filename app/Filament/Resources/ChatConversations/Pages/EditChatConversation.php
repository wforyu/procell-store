<?php

namespace App\Filament\Resources\ChatConversations\Pages;

use App\Filament\Resources\ChatConversations\ChatConversationResource;
use App\Models\ChatMessage;
use Filament\Resources\Pages\EditRecord;

class EditChatConversation extends EditRecord
{
    protected static string $resource = ChatConversationResource::class;

    public ?string $adminReply = '';

    public function mount(int|string $record): void
    {
        parent::mount($record);
        $this->form->fill();
    }

    protected function afterSave(): void
    {
        $reply = $this->data['admin_reply'] ?? null;
        if ($reply && trim($reply) !== '') {
            ChatMessage::create([
                'conversation_id' => $this->record->id,
                'user_id' => auth()->id(),
                'message' => $reply,
                'is_admin' => true,
            ]);
        }
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()->label('Simpan & Balas'),
            $this->getCancelFormAction(),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
