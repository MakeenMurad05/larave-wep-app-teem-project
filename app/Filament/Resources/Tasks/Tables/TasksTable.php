<?php

namespace App\Filament\Resources\Tasks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class TasksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable(),
                TextColumn::make('project.title')->label('Project'), // لإظهار اسم المشروع التابع له
                TextColumn::make('status')->badge(),
                TextColumn::make('priority')->badge(),
                TextColumn::make('assignedUser.name')->label('Assignee'),
                TextColumn::make('creator.name')->label('Created By'),
                TextColumn::make('due_date')->label('due_date'),
            ])
            ->filters([
                // 1. فلتر التصفية حسب المشروع
            SelectFilter::make('project_id')
                ->label('Project')
                ->relationship('project', 'title') // يجلب قائمة المشاريع تلقائياً
                ->searchable()
                ->preload(),

            // 2. فلتر التصفية حسب الحالة
            SelectFilter::make('status')
                ->label('Status')
                ->options([
                    'pending' => 'Pending',
                    'in_progress' => 'In Progress',
                    'review' => 'Review',
                    'blocked' => 'Blocked',
                    'completed' => 'Completed',
                ]),

            // 3. فلتر التصفية حسب الأولوية
            SelectFilter::make('priority')
                ->label('Priority')
                ->options([
                    'low' => 'Low',
                    'medium' => 'Medium',
                    'high' => 'High',
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
