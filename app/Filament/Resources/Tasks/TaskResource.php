<?php

namespace App\Filament\Resources\Tasks;

use App\Filament\Resources\Tasks\Pages\CreateTask;
use App\Filament\Resources\Tasks\Pages\EditTask;
use App\Filament\Resources\Tasks\Pages\ListTasks;
use App\Filament\Resources\Tasks\Pages\ViewTask;
use App\Filament\Resources\Tasks\Schemas\TaskForm;
use App\Filament\Resources\Tasks\Schemas\TaskInfolist;
use App\Filament\Resources\Tasks\Tables\TasksTable;
use App\Models\Task;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $form->schema([
            TextInput::make('title')
                ->required()
                ->disabled(fn () => auth()->user()->hasRole('Member')), 

            // الحالة: مسموح للجميع تعديلها
            Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'in_progress' => 'In Progress',
                    'completed' => 'Completed',
                ])
                ->required(),

            // الوصف: ممنوع تعديله للعضو
            Textarea::make('description')
                ->disabled(fn () => auth()->user()->hasRole('Member')),
                FileUpload::make('attachment') // افترضنا أن اسم العمود في الجدول هو attachment
                ->label('ملفات المهمة')
                ->directory('task-files') // المجلد الذي ستخزن فيه الملفات
                ->visibility('public')
                ->openable()
                ->downloadable()
                // العضو يمكنه الرفع، لكن لا يمكنه حذف الملفات القديمة إلا لو كان مديراً (اختياري)
                ->deletable(fn () => !auth()->user()->hasRole('Member')),
        ]);
    }
    

    public static function infolist(Schema $schema): Schema
    {
        return TaskInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TasksTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTasks::route('/'),
            'create' => CreateTask::route('/create'),
            'view' => ViewTask::route('/{record}'),
            'edit' => EditTask::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()->harRole('Member'))
        {
            return $query->where('assigned_to', auth()->id());
        }

        if (auth()->user()->hasRole('Manager'))
        {
            return $query->wherehas('project', function($q){
                $q->whereHas('users', function($u){
                    $u->where('users.id', auth()->id());
                });
            });
        }
        return $query;
    }
}
