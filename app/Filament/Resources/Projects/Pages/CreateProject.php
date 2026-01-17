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
        // إضافة معرف المستخدم الحالي إلى البيانات قبل الحفظ في القاعدة
        $data['created_by'] = auth()->id();
        return $data;
    }

    protected function afterCreate(): void
    {
        $project = $this->record;
            
            // جلب المشرفين (تأكد من وجود مستخدمين بهذا الدور)
            $admins = User::role('super_admin')->get();

            // نرسل الإشعار فقط إذا وجدنا مشرفين لتجنب الأخطاء
            if ($admins->count() > 0) {
                \Filament\Notifications\Notification::make()
                    ->title('New Project Started')
                    // استخدمنا ?? للتأكد أنه إذا لم يجد title لا ينهار الكود
                    ->body("Project **" . ($project->title ?? 'Untitled') . "** created by " . auth()->user()->name)
                    ->info()
                    ->sendToDatabase($admins); // أرسل للمجموعة مباشرة دون foreach
            }
    }
}
