<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $guarded = [];

    // relation to task model
    public function task()
    {
        return $this->belongsTo('App\Task');
    }

    // relation to user model
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
