<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'project_id',
        'department_id',
        'title',
        'description',
        'priority',
        'status',
        'due_date',
        'assigned_to',
        'created_by',
    ];

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

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function comments()
    {
        return $this->hasMany(TaskComment::class);
    }

    public function attachments()
    {
        return $this->hasMany(TaskAttachment::class);
    }
}
