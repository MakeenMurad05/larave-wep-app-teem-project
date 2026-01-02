<?php

namespace App\Filament\Member\Widgets;

use App\Models\Task;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MemberTaskStats extends BaseWidget
{
    protected function getStats(): array
    {
        // Get the current member's ID
        $userId = auth()->id();

        return [
            // 1. Pending Tasks
            Stat::make('Pending', Task::where('assigned_to', $userId)->where('status', 'pending')->count())
                ->description('Tasks waiting for you')
                ->descriptionIcon('heroicon-m-clock')
                ->color('gray'),

            // 2. In Progress
            Stat::make('In Progress', Task::where('assigned_to', $userId)->where('status', 'in_progress')->count())
                ->description('Currently working on')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('warning'),

            // 3. Completed
            Stat::make('Completed', Task::where('assigned_to', $userId)->where('status', 'completed')->count())
                ->description('Good job!')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
        ];
    }
}