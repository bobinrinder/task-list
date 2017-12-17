<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    // relation to task model
    public function task()
    {
        $this->hasOne('App\Task');
    }
}
