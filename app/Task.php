<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $guarded = [];

    protected $with = ['user', 'followers', 'assignments'];

    // relation to assignment model
    public function assignments()
    {
        return $this->hasMany('App\Assignment');
    }

    // relation to comment model
    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    // relation to follow model
    public function followers()
    {
        return $this->hasMany('App\Follow');
    }

    // relation to user model
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
