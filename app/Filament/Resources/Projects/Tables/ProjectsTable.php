<?php

namespace App\Filament\Resources\Projects\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\View\Components\BadgeComponent;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('department.name')
                ->label('Department')
                ->sortable(),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('status')
                    ->colors([
                        'info' => 'planning',
                        'success' => 'active',
                        'warning' => 'on_hold',
                        'gray' => 'completed',
                        'secondary' => 'archived',
                    ]),

                TextColumn::make('start_date')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('creator.name') 
                    ->label('Created By')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
