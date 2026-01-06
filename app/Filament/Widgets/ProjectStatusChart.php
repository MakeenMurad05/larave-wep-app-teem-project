<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use Filament\Widgets\ChartWidget;

class ProjectStatusChart extends ChartWidget
{
    protected ?string $heading = 'Project Status Chart';
protected static ?int $sort = 21;
    protected function getData(): array
    {
        $user = auth()->user();
        $query = Project::query();

        // فلترة للمانجر: يرى فقط مشاريعه الخاصة
        if ($user->hasRole('Manager')) {
            $query->where('created_by', $user->id);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Projects',
                    'data' => [
                        (clone $query)->where('status', 'planning')->count(),
                        (clone $query)->where('status', 'active')->count(),
                        (clone $query)->where('status', 'on_hold')->count(),
                        (clone $query)->where('status', 'completed')->count(),
                        (clone $query)->where('status', 'archived')->count(),
                    ],
                    'backgroundColor' => [
                        '#94a3b8', // Planning
                        '#22c55e', // Active
                        '#fbbf24', // On Hold
                        '#3b82f6', // Completed
                        '#ef4444', // Archived
                    ],
                    'borderColor' => 'transparent',
                ],
            ],
            'labels' => ['Planning', 'Active', 'On Hold', 'Completed', 'Archived'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
