<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $guarded = [];
    
    // relation to assignment model
    public function assignments()
    {
        $this->hasMany('App\Assignment');
    }

    // relation to comment model
    public function comments()
    {
        $this->hasMany('App\Comment');
    }

    // relation to follow model
    public function follows()
    {
        $this->hasMany('App\Follow');
    }
}
