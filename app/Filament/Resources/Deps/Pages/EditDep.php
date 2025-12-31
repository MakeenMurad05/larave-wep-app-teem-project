<?php

namespace App\Filament\Resources\Deps\Pages;

use App\Filament\Resources\Deps\DepResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDep extends EditRecord
{
    protected static string $resource = DepResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
