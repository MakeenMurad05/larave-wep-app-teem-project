<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Task;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->hasRole('Admin') || $authUser->hasRole('super_admin');

        if ($authUser->hasRole('Manager'))
            return $task->project->users->contains($authUser);
    }

    public function view(AuthUser $authUser, Task $task): bool
    {
        if ($authUser->hasRole('Admin') || $authUser->hasRole('super_admin'))
            return true;

        if ($authUser->hasRole('Manager') && $task->project->users->contains($authUser->id))
        {
            return true;
        }

        return $task->assigned_to === $authUser->id;
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->hasRole('Manager') || $authUser->hasRole('Admin')
        || $authUser->hasRole('super_admin');
    }

    public function update(AuthUser $authUser, Task $task): bool
    {
        return $authUser->hasRole('Admin') || $authUser->hasRole('Manager');

        return $task->assigned_to === $user->id;
    }

    public function delete(AuthUser $authUser, Task $task): bool
    {
        return $authUser->hasRole('Admin') || $authUser->hasRole('super_admin');

        if ($authUser->hasRole('Manager'))
            return $task->project->users->contains($authUser);
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