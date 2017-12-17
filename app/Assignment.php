<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    // relation to task model
    public function task()
    {
        $this->hasOne('App\Task');
    }

    // relation to task model
    public function user()
    {
        $this->hasOne('App\User');
    }
}
