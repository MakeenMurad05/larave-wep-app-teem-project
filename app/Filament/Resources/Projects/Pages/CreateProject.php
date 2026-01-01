<?php

namespace App\Filament\Resources\Projects\Pages;

use App\Filament\Resources\Projects\ProjectResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProject extends CreateRecord
{
    protected static string $resource = ProjectResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // إضافة معرف المستخدم الحالي إلى البيانات قبل الحفظ في القاعدة
        $data['created_by'] = auth()->id();

        return $data;
    }
}
