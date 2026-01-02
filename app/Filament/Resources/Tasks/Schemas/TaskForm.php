<?php

namespace App\Filament\Resources\Tasks\Schemas;

use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class TaskForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([

            TextInput::make('title')
                ->required()
                ->disabled(fn () => auth()->user()->hasRole('Member')),

            Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'in_progress' => 'In Progress',
                    'review' => 'Review',
                    'blocked' => 'Blocked',
                    'completed' => 'Completed',
                ])
                ->required(),

            Select::make('project_id')
                ->relationship('project', 'title') // Task belongsTo Project
                ->required(),

            Select::make('priority')
                ->options([
                    'low' => 'Low',
                    'medium' => 'Medium',
                    'high' => 'High',
                ]),

            Textarea::make('description')
                ->disabled(fn () => auth()->user()->hasRole('Member')),

Repeater::make('attachments')
    ->relationship()
    ->label('ملفات المهمة')
    ->schema([
        FileUpload::make('file_path')
            ->directory('task-files')
            ->visibility('public')
            ->multiple(false)      // لكل عنصر ملف واحد فقط
            ->required()           // validation
            ->dehydrated()         // ⭐ مهم جداً لإرسال القيمة
            ->storeFileNamesIn('file_name')
            ->afterStateUpdated(function ($state, callable $set) {
                // حجم الملف
                if ($state instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
                    $set('file_size', $state->getSize());
                }
            }),

        Hidden::make('file_name')
            ->dehydrateStateUsing(fn ($state, $get) => basename($get('file_path'))),

        Hidden::make('file_size')->default(0),
        Hidden::make('uploaded_by')->default(fn () => auth()->id()),
    ])
    ->addActionLabel('إضافة ملف'),

            DateTimePicker::make('due_date')
                ->default(now())
                ->minDate(now()),

            Select::make('assigned_to')
                ->relationship('assignedUser', 'name')
                ->searchable()
                ->preload()
                ->required(),

            Hidden::make('created_by')->default(auth()->id()),
        ]);
    }
}
