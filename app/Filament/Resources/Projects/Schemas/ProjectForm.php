<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Models\Department;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([

            TextInput::make('title')
                ->label('Project Title')
                ->required()
                ->maxLength(255),

            Textarea::make('description')
                ->required()
                ->columnSpanFull(),

            Select::make('status')
                ->options([
                    'planning' => 'Planning',
                    'active' => 'Active',
                    'on_hold' => 'On Hold',
                    'completed' => 'Completed',
                    'archived' => 'Archived',
                ])
                ->required()
                ->native(false),

            DateTimePicker::make('start_date')
                ->required()
                ->default(now()),

            DateTimePicker::make('end_date')
                ->after('start_date')
                ->required(),

            Select::make('department_id')
                ->label('Department')
                ->relationship('department', 'name')
                ->searchable()
                ->preload()
                ->required()
                ->visible(fn () => auth()->user()?->hasAnyRole(['Admin', 'super_admin']))
                ->default(fn () => auth()->user()?->department_id),

            Hidden::make('created_by')
                ->default(auth()->id()),
        ]);
    }
}