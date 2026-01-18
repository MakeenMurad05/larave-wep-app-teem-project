<?php

namespace App\Filament\Resources\Tasks\Pages;

use App\Filament\Resources\Tasks\TaskResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

use function Symfony\Component\Clock\now;

class CreateTask extends CreateRecord
{
    protected static string $resource = TaskResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['due_date'] = now();
        return $data;
    }

    protected function afterCreate(): void
    {
        // 1. Get the task
        $task = $this->record;

        // 2. Get the user object (using the raw ID)
        $recipient = \App\Models\User::find($task->assigned_to);

        // 3. Send simple notification
        if ($recipient) {
            Notification::make()
                ->title('New Task Assigned')
                ->body("Task: {$task->title}")
                ->success()
                ->sendToDatabase($recipient); 

                                try {
            \Illuminate\Support\Facades\Mail::raw(
                "تم إنشاء مهة جديدة في النظام.\n\n" .
                "المهمة: {$task->title}\n" .
                "بواسطة: " . auth()->user()->name,
                function ($message) use ($admin) {
                    $message->to($admin->email)
                            ->subject('تنبيه: مهمة جديدة - ' . config('app.name'));
                }
            );
        } catch (\Exception $e) {
            // في حال فشل الإيميل، سيستمر الكود ولن ينهار الموقع
            report($e);
        }
        }
    }
}
