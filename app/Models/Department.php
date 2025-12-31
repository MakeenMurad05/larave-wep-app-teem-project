<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'department';

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
