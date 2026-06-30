<?php

namespace App\Filament\Resources\PurchaseOrders\Pages;

use App\Filament\Resources\PurchaseOrders\PurchaseOrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePurchaseOrder extends CreateRecord
{
    protected static string $resource = PurchaseOrderResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (empty($data['order_number'])) {
            $data['order_number'] = 'PO-'.now()->format('Ymd').'-'.strtoupper(\Str::random(5));
        }

        return $data;
    }

    protected function afterSave(): void
    {
        $total = $this->record->items()->sum('subtotal');
        $this->record->update(['total_amount' => $total]);
    }
}
