<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles , LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'user_type',
        'department_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'role', 'department_id']) // Track permission changes
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "User {$eventName}");
    }

    public function getFilamentName(): string
    {
        return $this->name ?? $this->name; // بدل 'name'
    }


    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() !== 'admin')
        {
            return false;
        }

        return $this->hasAnyRole(['super_admin', 'Admin', 'Manager', 'Member']);
;
    }

    public function memberProjects()
    {
        return $this->belongsToMany(Project::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function activitylogs()
    {
        return $this->hasMany(ActivityLog::class, 'causer_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
   
    public function ownedProjects()
    {
        return $this->hasMany(Project::class, 'created_by');
    }

}
