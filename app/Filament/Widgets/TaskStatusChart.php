<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use Filament\Widgets\ChartWidget;

class TaskStatusChart extends ChartWidget
{
    protected ?string $heading = 'Task Status Chart';

    protected static ?int $sort = 1;

    protected function getData(): array
    {
        $query = Task::query();

        if (auth()->user()->hasRole('Member')) {
            $query->where('assigned_to', auth()->id());
        }

        if (auth()->user()->hasRole('Manager')) {
        $query->whereHas('project', function($q) {
            $q->where('created_by', auth()->id());
        });
        }

        return [
            'datasets' => [
                [
                    'label' => 'Tasks',
                    'data' => [
                        (clone $query)->where('status', 'pending')->count(),
                        (clone $query)->where('status', 'in_progress')->count(),
                        (clone $query)->where('status', 'review')->count(),
                        (clone $query)->where('status', 'blocked')->count(),
                        (clone $query)->where('status', 'completed')->count(),
                    ],
                    'backgroundColor' => [
                        '#fbbf24',
                        '#3b82f6',
                        '#a855f7',
                        '#ef4444',
                        '#22c55e',
                    ],
                    'borderColor' => 'transparent',
                ],
            ],
            'labels' => [
                'Pending',
                'In Progress',
                'Review',
                'Blocked',
                'Completed',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
