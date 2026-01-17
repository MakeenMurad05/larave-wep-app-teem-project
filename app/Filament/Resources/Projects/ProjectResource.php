<?php

namespace App\Filament\Resources\Projects;

use App\Filament\Resources\Projects\Pages\CreateProject;
use App\Filament\Resources\Projects\Pages\EditProject;
use App\Filament\Resources\Projects\Pages\ListProjects;
use App\Filament\Resources\Projects\RelationManagers\TasksRelationManager;
use App\Filament\Resources\Projects\Schemas\ProjectForm;
use App\Filament\Resources\Projects\Tables\ProjectsTable;
use Illuminate\Database\Eloquent\Builder;

use App\Models\Project;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;


class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return ProjectForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProjectsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            TasksRelationManager::class,
        ];
    }

    public static function getEloquentQuery(): Builder
    {
            $user = auth()->user();

            if (! $user) {
                return parent::getEloquentQuery()->whereRaw('1=0');
            }

            $query = parent::getEloquentQuery();

            if ($user->hasAnyRole(['Admin', 'super_admin'])) {
                return $query;
            }

            if ($user->hasRole('Manager')) {
                return $query->where('created_by', $user->id);
            }

            if ($user->department_id) {
                return $query->where('department_id', $user->department_id);
            }

            return $query->whereRaw('1=0');
       
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
