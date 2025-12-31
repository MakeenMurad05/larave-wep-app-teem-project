<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Models\Department;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('department_id') // 🟢 Select للمجموعة
                ->label('Department')
                ->options(Department::all()->pluck('name', 'id')) // الاسم مقابل الـ id
                ->required(),
                TextInput::make('title')
                    ->required(),
                Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                DateTimePicker::make('start_date')
                    ->required(),
                DateTimePicker::make('end_date')->after('start_date')
                    ->required(),
            ]);
    }
}
