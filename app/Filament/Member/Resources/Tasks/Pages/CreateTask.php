<?php

namespace App\Filament\Member\Resources\Tasks\Pages;

use App\Filament\Member\Resources\Tasks\TaskResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTask extends CreateRecord
{
    protected static string $resource = TaskResource::class;
}
