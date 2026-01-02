<?php

namespace App\Filament\Manager\Resources\Tasks;

use App\Filament\Manager\Resources\Tasks\Pages\CreateTask;
use App\Filament\Manager\Resources\Tasks\Pages\EditTask;
use App\Filament\Manager\Resources\Tasks\Pages\ListTasks;
use App\Filament\Manager\Resources\Tasks\Schemas\TaskForm;
use App\Filament\Manager\Resources\Tasks\Tables\TasksTable;
use App\Models\Task;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder; 
class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Task';

    public static function form(Schema $schema): Schema
{
    return $schema
        ->schema([
            // 1. Select Project (Only from my department)
            Forms\Components\Select::make('project_id')
                ->label('Project')
                ->relationship('project', 'title', function (EloquentBuilder $query) {
                    // This creates the "Where" clause for the dropdown list
                    return $query->where('department_id', auth()->user()->department_id);
                })
                ->required(),

            Forms\Components\TextInput::make('title')
                ->required(),
                
            Forms\Components\Textarea::make('description')
                ->columnSpanFull(),

            // 2. Assign to Member (Only show members from my department)
            Forms\Components\Select::make('assigned_to')
                ->label('Assign to Member')
                ->relationship('assignedUser', 'name', function (EloquentBuilder $query) {
                    // Only show users with the same department ID
                    return $query->where('department_id', auth()->user()->department_id);
                })
                ->searchable()
                ->required(),

            Forms\Components\Select::make('priority')
                ->options(['low' => 'Low', 'medium' => 'Medium', 'high' => 'High'])
                ->required(),
                
            Forms\Components\Select::make('status')
                 ->options([
                    'pending' => 'Pending', 
                    'in_progress' => 'In Progress', 
                    'completed' => 'Completed'
                 ])
                 ->default('pending')
                 ->required(),

            // Auto-fill Created By
            Forms\Components\Hidden::make('created_by')
                ->default(auth()->id()),
        ]);
}

    public static function table(Table $table): Table
    {
        return TasksTable::configure($table);
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
            'index' => ListTasks::route('/'),
            'create' => CreateTask::route('/create'),
            'edit' => EditTask::route('/{record}/edit'),
        ];
    }

    

    
}
