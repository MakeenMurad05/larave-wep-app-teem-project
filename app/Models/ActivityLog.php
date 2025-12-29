<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'activitylogs';

    public function user()
    {
        return $this->belongsTo(User::class, 'causer_id');
    }
}
