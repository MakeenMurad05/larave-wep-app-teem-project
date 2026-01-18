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
            $notification = Notification::make()
                ->title('New User Started')
                ->body("User **{$User->name}** created by " . auth()->user()->name)
                ->info();
                $notification->sendToDatabase($admin);
                $admin->notify($notification->toIlluminateNotification());
        }
    }
}
