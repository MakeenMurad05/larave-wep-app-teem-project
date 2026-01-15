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

        TextInput::make('title')
            ->label('Project Title') // غيرنا التسمية هنا لتكون دقيقة
            ->required()
            ->maxLength(255),

        Textarea::make('description')
            ->required()
            ->columnSpanFull(),

        DateTimePicker::make('start_date')
            ->required(),

        DateTimePicker::make('end_date')
            ->after('start_date')
            ->required(),
            
        // Department for Admin (selectable)
        Select::make('department_id')
            ->label('Department')
            ->relationship('department', 'name')
            ->searchable()
            ->preload()
            ->required()
            ->visible(fn () => auth()->user()->hasRole('Admin')),

        // Department for Manager (auto assigned)
        Hidden::make('department_id')
            ->default(fn () => auth()->user()->department_id)
            ->visible(fn () => auth()->user()->hasRole('Manager')),

        Hidden::make('created_by')->default(auth()->id()),
            ]);
    }
}
