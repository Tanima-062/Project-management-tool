<?php

namespace App\Modals;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    public function task()
    {
        return $this->belongsTo('App\Modals\Task', 'task_id','id');
    }
    public function user()
    {
        return $this->belongsTo('App\Modals\User', 'user_id', 'id')->withTrashed();
    }
}
