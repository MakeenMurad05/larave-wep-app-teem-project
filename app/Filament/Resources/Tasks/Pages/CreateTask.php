<?php

namespace App\Filament\Resources\Tasks\Pages;

use App\Filament\Resources\Tasks\TaskResource;
use Filament\Resources\Pages\CreateRecord;

use function Symfony\Component\Clock\now;

class CreateTask extends CreateRecord
{
    protected static string $resource = TaskResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
       
        $data['due_date'] = now();

        return $data;
    }
}
