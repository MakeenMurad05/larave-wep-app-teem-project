<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Project;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;
    
    public function before(AuthUser $authUser, $ability)
    {
        if ($authUser->hasRole('Admin') || $authUser->hasRole('super_admin')) {
            return true;
        }
    }

    public function viewAny(AuthUser $authUser, Project $project): bool
    {
        if ($authUser->hasAnyRole(['Admin', 'super_admin'])) 
        {
            return true;
        }

        if ($authUser->hasRole('Manager')) 
        {
            return $project->created_by === $authUser->id
                 || $project->creator->contains($authUser);
        }

        return false;
    }

    public function view(AuthUser $authUser, Project $project): bool
    {
        return $this->viewAny($authUser, $project);
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->hasAnyRole(['Admin', 'Manager']);
    }

    public function update(AuthUser $authUser, Project $project): bool
    {
        return $this->view($authUser, $project);
    }

    public function delete(AuthUser $authUser, Project $project): bool
    {
        return $this->view($authUser, $project);
    }

    public function restore(AuthUser $authUser, Project $project): bool
    {
        return $authUser->can('Restore:Project');
    }

    public function forceDelete(AuthUser $authUser, Project $project): bool
    {
        return $authUser->can('ForceDelete:Project');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Project');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Project');
    }

    public function replicate(AuthUser $authUser, Project $project): bool
    {
        return $authUser->can('Replicate:Project');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Project');
    }

}