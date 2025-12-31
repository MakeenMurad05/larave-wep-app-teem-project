<?php

namespace App\Filament\Resources\Deps\Pages;

use App\Filament\Resources\Deps\DepResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDeps extends ListRecords
{
    protected static string $resource = DepResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
