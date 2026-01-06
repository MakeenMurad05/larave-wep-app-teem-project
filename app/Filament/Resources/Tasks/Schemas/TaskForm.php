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
                ->required()
                ->visible(fn () => auth()->user()->hasRole('Member')),

            Select::make('project_id')
                ->relationship('project', 'title') // Task belongsTo Project
                ->required()
                ->disabled(fn () => auth()->user()->hasRole('Member')),

            Select::make('priority')
                ->options([
                    'low' => 'Low',
                    'medium' => 'Medium',
                    'high' => 'High',
                ])
                ->disabled(fn () => auth()->user()->hasRole('Member')),

            Textarea::make('description')
                ->disabled(fn () => auth()->user()->hasRole('Member')),

            Repeater::make('attachments')
                ->relationship()
                ->minItems(0)
                ->default([])
                ->label('Task Files')
                ->schema([
            FileUpload::make('file_path')
                ->required()
                ->directory('task-files')
                ->storeFileNamesIn('file_name')
                ->live() 
                ->afterStateUpdated(function ($state, callable $set) {
                    if ($state instanceof TemporaryUploadedFile) {
                        $set('file_size', $state->getSize());
                        $set('file_name', $state->getClientOriginalName());
                    }
                }),

            Hidden::make('file_name')
                ->dehydrateStateUsing(fn ($state, $get) => basename($get('file_path'))),

            Hidden::make('file_size')->default(0),
            Hidden::make('uploaded_by')->default(fn () => auth()->id()),
    ])
    ->addActionLabel('Add File'),


            Select::make('assigned_to')
                ->relationship('assignedUser', 'name')
                ->searchable()
                ->preload()
                ->disabled(fn () => auth()->user()->hasRole('Member')),

            Hidden::make('created_by')->default(auth()->id()),
        ]);
    }
}
