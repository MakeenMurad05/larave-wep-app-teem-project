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
        
        // Send to all Super Admins
        $admins = User::role('super_admin')->get();

        foreach ($admins as $admin) {
            Notification::make()
                ->title('New Project Started')
                ->body("Project **{$project->title}** created by " . auth()->user()->name)
                ->info()
                ->sendToDatabase($admin);
        }
    }
}
