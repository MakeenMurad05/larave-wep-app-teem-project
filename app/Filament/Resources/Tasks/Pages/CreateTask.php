<?php

namespace App\Filament\Resources\Tasks\Pages;

use App\Filament\Resources\Tasks\TaskResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

use Notification;

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
            \Filament\Notifications\Notification::make()
                ->title('New Task Assigned')
                ->body("Task: {$task->title}")
                ->success()
                ->sendToDatabase($recipient); 
        }
    }
}
