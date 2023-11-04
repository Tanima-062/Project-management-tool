<?php

namespace App\Modals;

use Illuminate\Database\Eloquent\Model;

class Week extends Model
{
    public function workLogs()
    {
        return $this->hasMany('App\Modals\WorkLog', 'week_id','id');
    }
}
