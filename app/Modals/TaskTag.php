<?php

namespace App\Modals;

use Illuminate\Database\Eloquent\Model;

class TaskTag extends Model
{
    protected $fillable = ['task_id', 'tag_id', 'created_at', 'updated_at'];

    public function task()
    {
        return $this->belongsTo('App\Modals\Task', 'task_id','id');
    }
    public function tag()
    {
        return $this->belongsTo('App\Modals\Tag', 'tag_id','id');
    }

    public function requestTask()
    {
        return $this->belongsTo('App\Modals\RequestTask', 'request_task_id','id');
    }
}
