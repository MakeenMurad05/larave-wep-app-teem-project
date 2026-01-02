<?php

namespace App\Filament\Manager\Resources\Projects;

use App\Filament\Manager\Resources\Projects\Pages\CreateProject;
use App\Filament\Manager\Resources\Projects\Pages\EditProject;
use App\Filament\Manager\Resources\Projects\Schemas\ProjectForm;
use App\Filament\Manager\Resources\Projects\Pages\ListProjects;
use App\Filament\Manager\Resources\Projects\Tables\ProjectsTable;
use App\Models\Project;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Schemas\Schema; 
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Columns\TextColumn;



class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Project';


    public static function getEloquentQuery(): EloquentBuilder
    {
        return parent::getEloquentQuery()
            ->where('department_id', auth()->user()->department_id);
    }

       public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                
                Textarea::make('description'), // Removed columnSpanFull if it causes issues, add back if supported
                
                DatePicker::make('start_date')
                    ->required(),
                    
                DatePicker::make('end_date'),

                Select::make('status')
                    ->options([
                        'planning' => 'Planning',
                        'active' => 'Active',
                        'completed' => 'Completed',
                    ])
                    ->default('planning')
                    ->required(),

                // --- MANAGER LOGIC (Hidden Fields) ---

                // 1. Auto-set Department ID
                Hidden::make('department_id')
                    ->default(auth()->user()->department_id),
                    
                // 2. Auto-set Created By
                Hidden::make('created_by')
                    ->default(auth()->id()),
            ]);
    }

      public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable(),
                TextColumn::make('status')->badge(),
                TextColumn::make('start_date')->date(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProjects::route('/'),
            'create' => CreateProject::route('/create'),
            'edit' => EditProject::route('/{record}/edit'),
        ];
    }



}
