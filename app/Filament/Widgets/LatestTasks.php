<?php

namespace App\Filament\Widgets;

use Filament\Actions\BulkActionGroup;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Task;
use Filament\Tables\Columns\TextColumn;

class LatestTasks extends TableWidget
{
    protected static ?int $sort = 10;
    public function table(Table $table): Table
    {
        return $table
            ->query(function (): Builder {
                $query = Task::query()->latest();

                if (auth()->user()->hasRole('Member')) {
                    $query->where('assigned_to', auth()->id());
                }

                return $query;
            })
            ->columns([
                TextColumn::make('title')->searchable(),
                TextColumn::make('project.title')->label('Project'), 
                TextColumn::make('status')->badge(),
                TextColumn::make('priority')->badge(),
                TextColumn::make('assignedUser.name')->label('Assignee'),
                TextColumn::make('creator.name')->label('Created By'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
