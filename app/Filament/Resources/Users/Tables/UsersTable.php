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
                    
                // القسم الأول: معلومات الحساب الأساسية
                Section::make('Account Information')
                    ->schema([
                        \Filament\Forms\Components\TextInput::make('name'),
                        \Filament\Forms\Components\TextInput::make('email'),
                        \Filament\Forms\Components\TextInput::make('user_type'),
                    ])->columns(2),

                // القسم الثاني: معلومات البروفايل المرتبطة
                Section::make('Profile Details')
                    ->schema([
                        \Filament\Forms\Components\FileUpload::make('profile.photo')
                            ->label('Profile Photo')
                            ->avatar()
                            ->disk('public'), // تأكد من استخدام نفس القرص

                Group::make([
                    \Filament\Forms\Components\TextInput::make('profile.first_name')
                        ->label('First Name'),
                    \Filament\Forms\Components\TextInput::make('profile.last_name')
                        ->label('Last Name'),
                    \Filament\Forms\Components\TextInput::make('profile.phone')
                        ->label('Phone Number'),
                    \Filament\Forms\Components\DatePicker::make('profile.birth_date')
                        ->label('Birth Date'),
                ])->columns(2),

                \Filament\Forms\Components\Textarea::make('profile.bio')
                    ->label('Bio')
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
