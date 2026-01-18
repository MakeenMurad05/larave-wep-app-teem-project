<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                IconColumn::make('is_active')
                    ->boolean(),
                TextColumn::make('user_type')
                    ->badge(),
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
            ->recordActions([
                EditAction::make(),
                ViewAction::make()
->form([
        // معلومات الحساب (من جدول users)
        Section::make('Account Information')
            ->schema([
                \Filament\Forms\Components\TextInput::make('name'),
                \Filament\Forms\Components\TextInput::make('email'),
                \Filament\Forms\Components\TextInput::make('user_type'),
            ])->columns(2),

        // معلومات البروفايل (باستخدام دالة relationship)
        Section::make('Profile Details')
            ->relationship('profile') // اسم الدالة الموجودة في موديل User
            ->schema([
                \Filament\Forms\Components\FileUpload::make('photo') // نكتب 'photo' مباشرة بدون كلمة 'profile.'
                    ->label('Profile Photo')
                    ->avatar()
                    ->disk('public')
                    ->formatStateUsing(fn ($state) => is_string($state) ? [$state] : $state),

                Group::make([
                    \Filament\Forms\Components\TextInput::make('first_name'), // نكتب اسم العمود مباشرة
                    \Filament\Forms\Components\TextInput::make('last_name'),
                    \Filament\Forms\Components\TextInput::make('phone'),
                    \Filament\Forms\Components\DatePicker::make('birth_date'),
                ])->columns(2),

                \Filament\Forms\Components\Textarea::make('bio')
                    ->columnSpanFull(),
            ])
    ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
