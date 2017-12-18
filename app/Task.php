<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $guarded = [];

    protected $with = ['user', 'completedUser', 'followers', 'assignments'];

    protected $dates = [
        'created_at',
        'updated_at',
        'due_date',
        'start_date',
        'end_date'
    ];

    public function setDueDateAttribute($value)
    {
        $this->attributes['due_date'] = strlen($value)? \Carbon\Carbon::createFromFormat('m/d/Y H:i A', $value): null;
    }

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

    // relation to user model
    public function completedUser()
    {
        return $this->belongsTo('App\User','completed_user_id', 'id');
    }
}
