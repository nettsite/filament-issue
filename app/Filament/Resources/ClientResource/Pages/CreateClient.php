<?php

namespace App\Filament\Resources\ClientResource\Pages;

use Filament\Notifications\Notification;
use App\Filament\Resources\ClientResource;
use Exception;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreateClient extends CreateRecord
{
    protected static string $resource = ClientResource::class;

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->info()
            ->title('Client Created')
            ->persistent()
            ->body('Name: ' . $this->record->name);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordCreation(array $data): Model
    {
        $record = new ($this->getModel())($data);

        if (
            static::getResource()::isScopedToTenant() &&
            ($tenant = Filament::getTenant())
        ) {
            return $this->associateRecordWithTenant($record, $tenant);
        }

        while (true) {
            DB::beginTransaction();
            try {
                $record->lab_num = $this->next_lab_num();
                $record->save();
                DB::commit();
                break;
            } catch (\Exception $e) {
                DB::rollback();
                if ('23000' == $e->getCode() && strpos($e->getMessage(), 'lab_num') !== false) {
                    continue;
                }
                throw $e;
            }
        }

        return $record;
    }

    private function next_lab_num(): string
    {
        return DB::select("SELECT
        CONCAT(
            LPAD(IFNULL(SUBSTRING(MAX(lab_num), 1, 3), 0) + 1, 3, 0),
            DATE_FORMAT(NOW(), '%d%m'),
            RIGHT(DATE_FORMAT(NOW(), '%y'), 1)
        ) AS next_lab_num
        FROM
            laboratory_dbo.demo2
        WHERE
            SUBSTRING(lab_num, CHAR_LENGTHlab_num) - 4) = CONCAT(DATE_FORMAT(NOW(), '%d%m'), RIGHT(DATE_FORMAT(NOW(), '%y'), 1))")[0]->next_lab_num;
    }
}
