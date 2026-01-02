<?php

namespace App\Filament\Manager\Resources\Tasks\Pages;

use App\Filament\Manager\Resources\Tasks\TaskResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTask extends CreateRecord
{
    protected static string $resource = TaskResource::class;
}
