<?php

namespace App\Filament\Member\Resources\Tasks;

use App\Filament\Member\Resources\Tasks\Pages\CreateTask;
use App\Filament\Member\Resources\Tasks\Pages\EditTask;
use App\Filament\Member\Resources\Tasks\Pages\ListTasks;
use App\Filament\Member\Resources\Tasks\Schemas\TaskForm;
use App\Filament\Member\Resources\Tasks\Tables\TasksTable;
use App\Models\Task;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Filament\Forms\Components\Builder ;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Task';

   public static function form(Schema $schema): Schema
{
    return $schema
        ->components([
            // 1. Title: READ ONLY
            Forms\Components\TextInput::make('title')
                ->disabled(), 

            // 2. Project: READ ONLY
            // We use a Select to show the related project name, but disabled
            Forms\Components\Select::make('project_id')
                ->relationship('project', 'title')
                ->label('Project')
                ->disabled(),

            // 3. Description: READ ONLY
            Forms\Components\Textarea::make('description')
                ->columnSpanFull()
                ->disabled(),

            // 4. STATUS: EDITABLE (This is the only enabled field)
            Forms\Components\Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'in_progress' => 'In Progress',
                    'blocked' => 'Blocked',
                    'completed' => 'Completed',
                ])
                ->required(),

            // 5. Priority: READ ONLY
            Forms\Components\Select::make('priority')
                ->options(['low' => 'Low', 'medium' => 'Medium', 'high' => 'High'])
                ->disabled(),

            // 6. Due Date: READ ONLY
            Forms\Components\DateTimePicker::make('due_date')
                ->disabled(),
        ]);
}

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Task Title
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                // Project Name (Dot notation accesses the relationship)
                TextColumn::make('project.title')
                    ->label('Project')
                    ->sortable(),

                // Status (Colored Badge)
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'in_progress' => 'warning',
                        'completed' => 'success',
                        default => 'info',
                    }),

                // Priority
                TextColumn::make('priority')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'high' => 'danger',
                        'medium' => 'warning',
                        'low' => 'success',
                    }),

                // Due Date
                TextColumn::make('due_date')
                    ->date()
                    ->sortable(),
            ])
            ->actions([
                // Member can ONLY Edit. No Delete button.
                EditAction::make(),
            ])
            // Remove Bulk Actions (Delete) so they can't delete multiple tasks
            ->bulkActions([]); 
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

    public static function getEloquentQuery(): EloquentBuilder
    {
        // Filter: Show tasks where 'assigned_to' matches the logged-in Member's ID
        return parent::getEloquentQuery()
            ->where('assigned_to', auth()->id());
    }

    
}
