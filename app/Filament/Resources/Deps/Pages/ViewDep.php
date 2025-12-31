<?php

namespace App\Filament\Resources\Deps\Pages;

use App\Filament\Resources\Deps\DepResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDep extends ViewRecord
{
    protected static string $resource = DepResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
