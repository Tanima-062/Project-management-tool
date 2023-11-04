<?php

namespace App\Modals;

use Illuminate\Database\Eloquent\Model;

class TeamTask extends Model
{
    protected $fillable = ['task_id', 'team_id', 'team_task_status', 'created_at', 'updated_at'];

    public function task()
    {
        return $this->belongsTo('App\Modals\Task', 'task_id','id');
    }
    public function team()
    {
        return $this->belongsTo('App\Modals\Team', 'team_id','id');
    }
}
