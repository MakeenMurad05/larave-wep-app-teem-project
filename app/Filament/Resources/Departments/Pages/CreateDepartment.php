<?php

namespace App\Filament\Resources\Departments\Pages;

use App\Filament\Resources\Departments\DepartmentResource;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateDepartment extends CreateRecord
{
    protected static string $resource = DepartmentResource::class;

    protected function afterCreate(): void
    {
        $Department = $this->record;
        
        // Send to all Super Admins
        $admins = User::role('super_admin')->get();

        foreach ($admins as $admin) {
            Notification::make()
                ->title('New Department Started')
                ->body("Department **{$Department->title}** created by " . auth()->user()->name)
                ->info()
                ->sendToDatabase($admin);
        }
    }
}
