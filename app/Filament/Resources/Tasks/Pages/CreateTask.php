<?php

namespace App\Filament\Resources\Tasks\Pages;

use App\Filament\Resources\Tasks\TaskResource;
use Illuminate\Support\Carbon;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;


class CreateTask extends CreateRecord
{
    protected static string $resource = TaskResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['due_date'] = Carbon::now()->toDateTimeString();;
        return $data;
    }

    protected function afterCreate(): void
    {
        $task = $this->record;

        if ($task->assigned_to) {
            $recipient = User::find($task->assigned_to);

            if ($recipient) {
                Notification::make()
                    ->title('New Task Assigned')
                    ->body("Task: {$task->title}")
                    ->success()
                    ->sendToDatabase($recipient);
            }
        }
    }
}
