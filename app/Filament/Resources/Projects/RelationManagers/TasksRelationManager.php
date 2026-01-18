<?php

namespace App\Filament\Resources\Projects\RelationManagers;

use App\Models\User;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class TasksRelationManager extends RelationManager
{
    protected static string $relationship = 'tasks';

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['due_date'] = now();

        return $data;
    }


    public function form(Schema $schema): Schema
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
                ->label('Assigned user')
                ->searchable()
                ->getSearchResultsUsing(fn (string $search) =>
                    User::whereHas('roles', fn ($q) => $q->where('name', 'Member'))
                        ->where('name', 'like', "%{$search}%")
                        ->pluck('name', 'id')
                )
                ->getOptionLabelUsing(fn ($value) => User::find($value)?->name)
                ->disabled(fn () => auth()->user()->hasRole('Member')),

            Hidden::make('created_by')->default(auth()->id()),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('priority')
                    ->badge(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('due_date')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('assignedUser.name')
                    ->label('assigned_to')
                    ->sortable(),
                TextColumn::make('creator.name')
                    ->label('created_by')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                
                CreateAction::make()
                ->mutateFormDataUsing(function (array $data): array {
                            // إذا كانت المصفوفة موجودة، نقوم بفلترتها
                            $data['due_date'] = now()->toDateTimeString();
                            
                            if (!empty($data['attachments'])) {
                                $data['attachments'] = array_filter($data['attachments'], function ($attachment) {
                                    // لا نسمح بمرور السجل إلا إذا كان ملف_بث يحتوي على قيمة (أي تم رفع ملف فعلي)
                                    return !empty($attachment['file_path']);
                                });
                            }
                            return $data;
                            })
                ->visible(fn ($livewire) => $livewire->ownerRecord->status !== 'completed'),
                            
                AssociateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DissociateAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
