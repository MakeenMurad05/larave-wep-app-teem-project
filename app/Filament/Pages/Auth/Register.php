<?php

namespace App\Filament\Pages\Auth;

// 1. لاحظ هنا: قمنا بتسمية الكلاس الأصلي BaseRegister
use Filament\Auth\Pages\Register as BaseRegister;
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
}