<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Tasks', Task::count()),
            Stat::make('Projects', Project::count()),
            Stat::make('Users', User::count()),
            Stat::make('Pending Tasks', Task::where('status', 'pending')->count()),
            Stat::make('In_progress Tasks', Task::where('status', 'in_progress')->count()),
            Stat::make('Completed Tasks', Task::where('status', 'completed')->count()),
        ];
    }
}
