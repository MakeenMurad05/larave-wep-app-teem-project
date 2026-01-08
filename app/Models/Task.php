<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Task extends Model
{
    use LogsActivity;
    protected $table = 'tasks';

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'priority',
        'status',
        'due_date',
        'assigned_to',
        'created_by',
    ];

    protected static function booted()
    {
        static::saved(function ($task) {
            if ($task->project) {
                // استدعاء دالة التحديث التي كتبتها أنت في موديل Project
                $task->project->updateProgress();
            }
        });
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function comments()
    {
        return $this->hasMany(TaskComment::class);
    }

    public function attachments()
    {
        return $this->hasMany(TaskAttachment::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'status', 'priority', 'description']) // Log these columns
            ->logOnlyDirty() // Only log changes (don't log if nothing changed)
            ->setDescriptionForEvent(fn(string $eventName) => "This task has been {$eventName}");
    }

    
}
