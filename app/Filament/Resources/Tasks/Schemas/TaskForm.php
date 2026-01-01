<?php

namespace App\Filament\Resources\Tasks\Schemas;


use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class TaskForm
{

    public static function configure(Schema $schema): Schema
    {
            return $schema->schema([
            TextInput::make('title')
                ->required()
                ->disabled(fn () => auth()->user()->hasRole('Member')), 

            // الحالة: مسموح للجميع تعديلها
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
            ->relationship('project', 'title') // هنا تعمل لأن Task ينتمي لـ Project
            ->required(),

        Select::make('assigned_to') // استخدم اسم العمود في قاعدة البيانات
            ->relationship('assignedUser', 'name') // استخدم اسم الدالة المعرفة في الموديل
            ->label('Assign to Member')
            ->required(),
            
        Select::make('priority')
            ->options(['low' => 'Low', 'medium' => 'Medium', 'high' => 'High']),

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

            DatePicker::make('due_date'),
        ]);
    }
}
