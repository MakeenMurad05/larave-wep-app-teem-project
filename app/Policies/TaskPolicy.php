<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Project;
use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;
    
    public function before(User $user, $ability)
    {
        if ($user->hasRole('super_admin') || $user->hasRole('Admin')) 
        {
            return true;
        }

        return null; 
    }

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->hasAnyRole(['super_admin', 'Admin', 'Manager', 'Member']);
    }

    public function view(AuthUser $authUser, Task $task): bool
    {
        if ($authUser->hasAnyRole(['super_admin', 'Admin', 'Manager'])) {
            return true;
        }

        return $authUser->hasRole('Member')
            && $task->assigned_to === $authUser->id;
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->hasAnyRole(['super_admin', 'Admin', 'Manager']);
    }

    public function update(AuthUser $authUser, Task $task): bool
    {
        if ($authUser->hasAnyRole(['super_admin', 'Admin', 'Manager'])) {
            return true;
        }

        // Member: فقط لو كانت المهمة له
        return $authUser->hasRole('Member')
            && $task->assigned_to === $authUser->id;
    }

    public function delete(AuthUser $authUser, Task $task): bool
    {
        return $authUser->hasAnyRole(['super_admin', 'Admin', 'Manager']);
    }

    public function restore(AuthUser $authUser, Task $task): bool
    {
        return $authUser->hasRole('Admin') || $authUser->hasRole('super_admin');
    }

    public function forceDelete(AuthUser $authUser, Task $task): bool
    {
        return $authUser->hasRole('Admin') || $authUser->hasRole('super_admin');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->hasRole('Admin') || $authUser->hasRole('super_admin');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->hasRole('Admin') || $authUser->hasRole('super_admin');
    }

    public function replicate(AuthUser $authUser, Task $task): bool
    {
        return $authUser->hasRole('Admin') || $authUser->hasRole('super_admin');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->hasRole('Admin') || $authUser->hasRole('super_admin');

        if ($authUser->hasRole('Manager'))
            return $task->project->users->contains($authUser);
    }

    public function uploadFile(AuthUser $authUser, Task $task): bool
    {
        return $task->assigned_to === $authUser->id || $authUser->hasRole('Manager');
    }



}