<?php

namespace App\Filament\Resources\Projects\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Tables\Columns\ProgressBarColumn;
use RyanChandler\FilamentProgressColumn\ProgressColumn;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            
            ->columns([

                TextColumn::make('department.name')
                ->label('Department')
                ->sortable(),
                TextColumn::make('title')->searchable(),
                TextColumn::make('status')->badge(),
                TextColumn::make('start_date')->label('start_date'), 
                TextColumn::make('end_date')->badge(),
                TextColumn::make('created_at')->label('created_at'),
                TextColumn::make('creator.name')->label('Created By'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                ProgressColumn::make('progress')
                ->label('نسبة الإنجاز')
                ->getStateUsing(function ($record) {
                    $total = $record->tasks()->count();
                    if ($total === 0) return 0;
                    $completed = $record->tasks()->where('status', 'completed')->count();
                    return ($completed / $total) * 100;
                })
                ->color('success'),
            ])
            ->filters([
                // 1. فلتر حسب القسم
                SelectFilter::make('department_id')
                    ->label('القسم')
                    ->relationship('department', 'name')
                    ->searchable()
                    ->preload(),

                // 2. فلتر حسب الحالة
                SelectFilter::make('status')
                    ->label('حالة المشروع')
                    ->options([
                        'pending' => 'Pending',
                        'in_progress' => 'In progress',
                        'review' => 'Review',
                        'blocked' => 'Blocked',
                        'completed' => 'Completed',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
