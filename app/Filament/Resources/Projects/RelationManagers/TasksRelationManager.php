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
use Filament\Forms\Components\DateTimePicker;
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

class TasksRelationManager extends RelationManager
{
    protected static string $relationship = 'tasks';

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
                ->label('Task Files')
                ->schema([
                    FileUpload::make('file_path')
                        ->directory('task-files')
                        ->visibility('public')
                        ->multiple(false)      // لكل عنصر ملف واحد فقط
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
    ->addActionLabel('Add File'),

            Select::make('assigned_to')
                ->relationship('assignedUser', 'name')
                ->searchable()
                ->preload()
                ->required(),

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
                            if (!empty($data['attachments'])) {
                                $data['attachments'] = array_filter($data['attachments'], function ($attachment) {
                                    // لا نسمح بمرور السجل إلا إذا كان ملف_بث يحتوي على قيمة (أي تم رفع ملف فعلي)
                                    return !empty($attachment['file_path']);
                                });
                            }
                            return $data;
                            }),
                            
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
