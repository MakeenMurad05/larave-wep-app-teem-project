<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'projects';

    protected $fillable = [
        'department_id',
        'title',
        'description',
        'start_date',
        'end_date',
        'created_by'
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
