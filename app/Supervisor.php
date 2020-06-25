<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supervisor extends Model
{
    protected $fillable = [
        'user_id'
    ];

    public function user()
    {
    	return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'supervisors_users')->with('moodleuser')->with('roles');
    }

    public function role()
    {
        return $this->user->roles->first();
    }
}
