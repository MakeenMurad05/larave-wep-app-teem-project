<?php

namespace App\Filament\Resources\ActivityLogs\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class ActivityLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // 1. User
                TextColumn::make('causer.name')
                    ->label('User')
                    ->placeholder('System')
                    ->searchable(),

                // 2. Action
                TextColumn::make('description')
                    ->badge()
                    ->colors([
                        'success' => 'created',
                        'warning' => 'updated',
                        'danger' => 'deleted',
                    ]),

                // 3. Subject (e.g. Task)
                TextColumn::make('subject_type')
                    ->label('Model')
                    ->formatStateUsing(fn ($state) => class_basename($state)),

                TextColumn::make('subject_id')
                    ->label('ID'),

                // 4. Time
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                ViewAction::make(), // Only View, no Edit/Delete
            ]);
    }
}