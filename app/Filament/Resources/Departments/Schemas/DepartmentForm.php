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
                ->label('department name')
                ->required()
                ->unique(table: 'department', column: 'name', ignoreRecord: true) // هذا هو السطر المطلوب
                ->validationMessages([
                    'unique' => 'هذا القسم موجود مسبقاً، يرجى اختيار اسم آخر.',
                ])
                ->maxLength(255),

            // حقل الوصف - اختياري (nullable)
            Textarea::make('description')
                ->label('description')
                ->rows(5)
                ->columnSpanFull(), // ليأخذ المساحة كاملة بالعرض
            ]);
    }
}
