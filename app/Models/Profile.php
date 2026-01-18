<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $table = 'profile';
    

    protected $fillable = [
        'users_id',
        'first_name',
        'last_name',
        'phone',
        'photo',
        'bio',
        'birth_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class , 'users_id');
    }
}
