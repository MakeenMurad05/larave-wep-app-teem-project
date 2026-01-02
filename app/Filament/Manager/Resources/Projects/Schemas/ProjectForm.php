<?php

namespace App\Filament\Manager\Resources\Projects\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('department_id')
                    ->required()
                    ->numeric(),
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Select::make('status')
                    ->options([
            'planning' => 'Planning',
            'active' => 'Active',
            'on_hold' => 'On hold',
            'completed' => 'Completed',
            'archived' => 'Archived',
        ])
                    ->default('planning')
                    ->required(),
                DateTimePicker::make('start_date')
                    ->required(),
                DateTimePicker::make('end_date'),
                TextInput::make('created_by')
                    ->required()
                    ->numeric(),
            ]);
    }
}
