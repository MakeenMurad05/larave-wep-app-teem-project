<?php

namespace App\Filament\Member\Resources\Tasks\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class TaskForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('project_id')
                    ->required()
                    ->numeric()
                    ->disabled(),

                TextInput::make('title')
                    ->required()
                    ->disabled(),

                Textarea::make('description')
                    ->default(null)
                    ->columnSpanFull()
                    ->disabled(),

                Select::make('priority')
                    ->options(['low' => 'Low', 'medium' => 'Medium', 'high' => 'High'])
                    ->required()
                    ->disabled(),

                Select::make('status')
                    ->options([
            'pending' => 'Pending',
            'in_progress' => 'In progress',
            'review' => 'Review',
            'blocked' => 'Blocked',
            'completed' => 'Completed',
        ])
                    ->required(),
                DateTimePicker::make('due_date')
                ->disabled(),

                TextInput::make('assigned_to')
                    ->numeric()
                    ->default(null)
                    ->hidden(),

                TextInput::make('created_by')
                    ->required()
                    ->numeric()
                    ->hidden(),
            ]);
    }
}
