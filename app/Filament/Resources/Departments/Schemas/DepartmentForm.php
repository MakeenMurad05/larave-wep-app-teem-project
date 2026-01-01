<?php

namespace App\Filament\Resources\Departments\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DepartmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // حقل الاسم - إلزامي بناءً على الـ Migration
            TextInput::make('name')
                ->label('اسم القسم')
                ->required()
                ->maxLength(255),

            // حقل الوصف - اختياري (nullable)
            Textarea::make('description')
                ->label('الوصف')
                ->rows(5)
                ->columnSpanFull(), // ليأخذ المساحة كاملة بالعرض
            ]);
    }
}
