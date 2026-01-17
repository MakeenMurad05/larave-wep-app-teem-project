<?php

namespace App\Filament\Resources\Tasks\Schemas;

use App\Models\Project;
use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
// تم تغيير الـ use هنا من Scout إلى Eloquent
use Illuminate\Database\Eloquent\Builder; 
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
                ->visible(fn () => !auth()->user()->hasRole('Member')),

            Select::make('project_id')
                ->relationship(
                    name: 'project', 
                    titleAttribute: 'title',
                    modifyQueryUsing: function (Builder $query, $get) {
                        // جلب الـ ID الخاص بالمشروع المرتبط بالمهمة الحالية
                        $currentProjectId = $get('project_id');

                        return $query
                            ->where(function ($q) use ($currentProjectId) {
                                // إظهار المشاريع غير المكتملة
                                $q->where('status', '!=', 'completed')
                                // وأيضاً إظهار المشروع الحالي حتى لو كان مكتملاً لكي لا يختفي من الحقل
                                ->orWhere('id', $currentProjectId);
                            })
                            ->when(auth()->user()->hasRole('Manager'), function ($q) {
                                return $q->where('created_by', auth()->id());
                            });
                    }
                )
                ->required()
                ->disabled(fn () => auth()->user()->hasRole('Member'))
                // إضافة رسالة توضيحية للمستخدم (اختياري)
                ->helperText('The complete project is not showing in the list'),

            Select::make('priority')
                ->options([
                    'low' => 'Low',
                    'medium' => 'Medium',
                    'high' => 'High',
                ])
                ->disabled(fn () => auth()->user()->hasRole('Member')),

            Textarea::make('description')
                ->disabled(fn () => auth()->user()->hasRole('Member')),

            Textarea::make('Comments')
            ->visible(fn () => auth()->user()->hasRole('Member')),

            Select::make('assigned_to')
            ->relationship(
                name: 'assignedUser',
                titleAttribute: 'name',
                modifyQueryUsing: function (Builder $query) {
                    // 1. First, apply the Role Filter (Only workers)
                    $query->whereHas('roles', function (Builder $q) {
                        $q->where('name', 'Member');
                    });

                    // 2. Then, apply the Department Filter (Only my team)
                    // (We check if the user is a Manager first to apply this restriction)
                    if (auth()->user()->hasRole('Manager')) {
                        $query->where('department_id', auth()->user()->department_id);
                    }
                    
                    return $query;
                }
            )
            ->searchable()
            ->preload()
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

            Textarea::make('comment_text')
                ->label('Comment')
                ->required()
                ->rows(3),

            Hidden::make('user_id')
                ->default(fn () => auth()->id()),

        

            Hidden::make('created_by')->default(auth()->id()),
        ]);
    }
}