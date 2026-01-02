<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskAttachment extends Model
{
    protected $table = 'task_attachments';

    protected $fillable = [
        'task_id',
        'file_name',
        'file_path',
        'file_size',
        'uploaded_by'
    ];

    
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
