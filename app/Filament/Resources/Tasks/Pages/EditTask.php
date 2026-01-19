<?php

namespace App\Filament\Resources\Tasks\Pages;

use App\Filament\Resources\Tasks\TaskResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Notification;

class EditTask extends EditRecord
{
    protected static string $resource = TaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (auth()->user()->hasRole('Member')) {
            return [
                'status' => $data['status'],
            ];
        }

        return $data;
    }

    protected function afterSave(): void
    {
        $task = $this->record;

        // Check if status changed to 'completed'
        if ($task->status === 'completed') {
            // Find the Manager who created the task (or project owner)
            $manager = \App\Models\User::find($task->created_by);

            if ($manager) {
                \Filament\Notifications\Notification::make()
                    ->title('Task Completed')
                    ->body("**{$task->assignedUser->name}** finished task: {$task->title}")
                    ->success()
                    ->actions([
                        Action::make('view')
                            ->button()
                            ->url(TaskResource::getUrl('edit', ['record' => $task->id])),
                    ])
                    ->sendToDatabase($manager);
            }
        }
    }
}
