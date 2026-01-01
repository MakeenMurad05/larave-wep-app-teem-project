<?php

namespace App\Filament\Resources\Departments;

use App\Filament\Resources\Departments\Pages\CreateDep;
use App\Filament\Resources\Departments\Pages\EditDep;
use App\Filament\Resources\Departments\Pages\ListDeps;
use App\Filament\Resources\Departments\Pages\ViewDep;
use App\Filament\Resources\Departments\Schemas\DepartmentForm;
use App\Filament\Resources\Departments\Schemas\DepartmentInfolist;
// أضف هذا السطر مع بقية الـ use في الأعلى
use App\Filament\Resources\Departments\Tables\DepartmentsTable; 
use App\Models\Department;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DepartmentResource extends Resource
{
    protected static ?string $model = Department::class;
    protected static ?string $navigationLabel = 'Departments'; // الاسم في القائمة
    protected static ?string $modelLabel = 'Department'; // اسم العنصر المفرد
    protected static ?string $pluralModelLabel = 'Departments'; // اسم الجمع
    protected static ?string $slug = 'departments'; // الرابط في المتصفح

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return DepartmentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DepartmentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DepartmentsTable::configure($table);
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
            'index' => ListDeps::route('/'),
            'create' => CreateDep::route('/create'),
            'view' => ViewDep::route('/{record}'),
            'edit' => EditDep::route('/{record}/edit'),
        ];
    }
}
