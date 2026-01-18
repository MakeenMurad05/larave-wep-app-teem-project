<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function afterCreate(): void
    {
        $User = $this->record;
        
        // Send to all Super Admins
        $admins = User::role('super_admin')->get();

        foreach ($admins as $admin) {
            Notification::make()
                ->title('New User Started')
                ->body("User **{$User->name}** created by " . auth()->user()->name)
                ->info()
                ->sendToDatabase($admin);

                try {
            \Illuminate\Support\Facades\Mail::raw(
                "تم إنشاء مستخدم جديد في النظام.\n\n" .
                "اسم المستخدم: {$User->name}\n" .
                "بواسطة: " . auth()->user()->name,
                function ($message) use ($admin) {
                    $message->to($admin->email)
                            ->subject('تنبيه: مستخدم جديد - ' . config('app.name'));
                }
            );
        } catch (\Exception $e) {
            // في حال فشل الإيميل، سيستمر الكود ولن ينهار الموقع
            report($e);
        }
        }
    }
}
