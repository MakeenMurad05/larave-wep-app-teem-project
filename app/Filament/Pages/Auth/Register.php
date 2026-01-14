<?php

namespace App\Filament\Pages\Auth;

// 1. لاحظ هنا: قمنا بتسمية الكلاس الأصلي BaseRegister
use Filament\Auth\Pages\Register as BaseRegister;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;

// 2. هنا نرث من الاسم الجديد BaseRegister
class MyRegister extends BaseRegister
{
    protected function handleRegistration(array $data): Model
    {
        $data['user_type'] = 'Member';
        $data['is_active'] = true;

        $user = parent::handleRegistration($data);

        $user->assignRole('Member'); 

        return $user;
    }

    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                        
                        // أضف هذا الجزء هنا
                        Select::make('department_id')
                            ->label('Department')
                            ->relationship('department', 'name') // يفترض وجود علاقة في مودل User
                            ->required()
                            ->preload()
                            ->searchable(),
                    ])
                    ->statePath('data'),
            ),
        ];
    }
}