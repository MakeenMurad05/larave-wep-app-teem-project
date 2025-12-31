<?php

namespace App\Filament\Resources\Deps;

use App\Filament\Resources\Deps\Pages\CreateDep;
use App\Filament\Resources\Deps\Pages\EditDep;
use App\Filament\Resources\Deps\Pages\ListDeps;
use App\Filament\Resources\Deps\Pages\ViewDep;
use App\Filament\Resources\Deps\Schemas\DepForm;
use App\Filament\Resources\Deps\Schemas\DepInfolist;
use App\Filament\Resources\Deps\Tables\DepsTable;
use App\Models\Dep;
use App\Models\Department;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DepResource extends Resource
{
    protected static ?string $model = Department::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return DepForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DepInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DepsTable::configure($table);
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
