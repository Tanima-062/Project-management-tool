<?php

namespace App\Modals;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Assignee extends Model
{
    use SoftDeletes;

    // shovon modification starts

    public function userWithTrashed()
    {
        return $this->belongsTo('App\Modals\User', 'user_id', 'id')->withTrashed();
    }

    // shovon modification ends

    public function task()
    {
        return $this->belongsTo('App\Modals\Task', 'task_id', 'id');
    }
   
    public function user()
    {
        return $this->belongsTo('App\Modals\User', 'user_id', 'id');
    }


}
