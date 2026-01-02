<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'departments';

        protected $fillable = [
        'id',
        'name',
        'description',
    ];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
