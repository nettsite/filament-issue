<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // if (empty($data['client_id'])) {
        //     dd($data);
        // }

        return $data;
    }

    protected function handleRecordCreation(array $data): Order
    {
        // if (empty($data['client_id'])) {
        //     dd($data);
        // }

        return static::getModel()::create($data);
    }
}
