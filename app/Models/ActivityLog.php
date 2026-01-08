<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';

    protected $fillable = [
        'log_name',
        'description',
        'subject_type',
        'subject_id',
        'causer_id',
        'properties'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class, 'causer_id');
    }
}
