<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 150; 
// يجعل الودجت يمتد على كامل العرض المتاح في الأعلى
protected int | string | array $columnSpan = 'full';
    // لتوزيع الكروت بشكل متناسق (مثلاً 3 كروت في الصف الواحد)
    //protected static ?int $columns = 3;

    protected function getStats(): array
    {
        $user = auth()->user();

        $taskQuery = Task::query();
        $projectQuery = Project::query();

        if ($user->hasRole('Member')) {
            $taskQuery->where('assigned_to', $user->id);
        }
        else if ($user->hasRole('Manager'))
        {
            $taskQuery->whereHas('project', function($query) use ($user){
                $query->where('created_by', $user->id);
            });

            $projectQuery->where('created_by', $user->id);
        }

        $stats = [
            Stat::make('Total Tasks', (clone $taskQuery)->count())
                ->description('All assigned tasks')
                ->icon('heroicon-m-clipboard-document-list')
                ->color('primary'),

            Stat::make('Pending Tasks', (clone $taskQuery)->where('status', 'pending')->count())
                ->description('Tasks waiting to start')
                ->icon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('In Progress', (clone $taskQuery)->where('status', 'in_progress')->count())
                ->description('Currently active tasks')
                ->icon('heroicon-m-arrow-path')
                ->color('info'),

            Stat::make('Review Tasks', (clone $taskQuery)->where('status', 'review')->count())
                ->description('Tasks under quality check')
                ->icon('heroicon-m-magnifying-glass')
                ->color('secondary'),

            Stat::make('Completed Tasks', (clone $taskQuery)->where('status', 'completed')->count())
                ->description('Successfully finished tasks')
                ->icon('heroicon-m-check-badge')
                ->color('success'),

            Stat::make('Blocked Tasks', (clone $taskQuery)->where('status', 'blocked')->count())
                ->description('Tasks with issues')
                ->icon('heroicon-m-x-circle')
                ->color('danger'),
        ];


        if ($user->hasAnyRole(['Admin', 'super_admin', 'Manager'])) {
            $stats[] = Stat::make('Planning Projects', (clone $projectQuery)->where('status', 'planning')->count())
                ->description('Projects in planning phase')
                ->icon('heroicon-m-pencil-square')
                ->color('gray');

            $stats[] = Stat::make('Active Projects', (clone $projectQuery)->where('status', 'active')->count())
                ->description('Ongoing projects')
                ->icon('heroicon-m-rocket-launch')
                ->color('success');

            $stats[] = Stat::make('On Hold Projects', (clone $projectQuery)->where('status', 'on_hold')->count())
                ->description('Paused projects')
                ->icon('heroicon-m-pause-circle')
                ->color('warning');

            $stats[] = Stat::make('Completed Projects', (clone $projectQuery)->where('status', 'completed')->count())
                ->description('Finished projects')
                ->icon('heroicon-m-archive-box')
                ->color('info');
        }


        if ($user->hasAnyRole(['Admin', 'super_admin', 'Manager'])) {
            $stats[] = Stat::make('My Projects', $projectQuery->count())
                ->description($user->hasRole('Manager') ? 'My Projects' : 'Total projects in the system')
                ->chart([7, 2, 10, 3, 15, 4, 17]) // شكل جمالي (اختياري)
                ->color('warning');
        }

        // 4. إضافة إحصائيات المستخدمين للأدمن فقط
    if ($user->hasAnyRole(['Admin', 'super_admin'])) {
            $stats[] = Stat::make('Total Users', User::count())
                ->description('All users in the system')
                ->icon('heroicon-m-users')
                ->color('gray');
        }


        return $stats;
    }
}
