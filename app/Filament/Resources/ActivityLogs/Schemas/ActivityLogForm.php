<?php

namespace App\Filament\Resources\ActivityLogs\Schemas;

use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Forms\Components\KeyValue;

class ActivityLogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(2)
                    ->schema([
                        // New Data (JSON)
                        KeyValue::make('properties.attributes')
                            ->label('New Data')
                            ->columnSpan(1),
                        
                        // Old Data (JSON)
                        KeyValue::make('properties.old')
                            ->label('Old Data')
                            ->columnSpan(1),
                    ]),
            ]);
    }
}