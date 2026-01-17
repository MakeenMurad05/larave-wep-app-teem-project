<?php

namespace App\Filament\Resources\Projects\Pages;

use App\Filament\Resources\Projects\ProjectResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;



class CreateProject extends CreateRecord
{
    protected static string $resource = ProjectResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (!$user->hasAnyRole(['Admin', 'super_admin'])) 
        {
            $data['department_id'] = $user->department_id;
        }
        // إضافة معرف المستخدم الحالي إلى البيانات قبل الحفظ في القاعدة
        $data['created_by'] = auth()->id();
        return $data;
    }

    protected function afterCreate(): void
    {

    }
}
