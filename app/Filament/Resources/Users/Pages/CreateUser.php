<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Mail\NewUserAlert;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Mail;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function afterCreate(): void
    {
        $newUser = $this->record;
        $creator = auth()->user();
        
        // جلب المشرفين
        $admins = User::role('Admin')->get();

        foreach ($admins as $admin) {
            // 1. إرسال إشعار قاعدة البيانات (Filament Notification)
            Notification::make()
                ->title('New User Started')
                ->body("User **{$newUser->name}** created by {$creator->name}")
                ->info()
                ->sendToDatabase($admin);

            // 2. إرسال الإيميل باستخدام كلاس NewUserAlert
            try {
                Mail::to($admin->email)->send(new NewUserAlert($newUser, $creator));
            } catch (\Exception $e) {
                report($e);
            }
        }
    }
}
