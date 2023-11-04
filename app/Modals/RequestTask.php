<?php

namespace App\Modals;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RequestTask extends Model
{
    use SoftDeletes;

    public function requestFrom()
    {
        return $this->belongsTo('App\Modals\User', 'request_from','id');
    }

    public function assignBy()
    {
        return $this->belongsTo('App\Modals\User', 'assign_by_id','id');
    }

    public function assignByWithTrashed()
    {
        return $this->belongsTo('App\Modals\User', 'assign_by_id','id')->withTrashed();
    }

    public function attachments()
    {
        return $this->hasMany('App\Modals\Attachment', 'request_task_id','id');
    }

    public function comments()
    {
        return $this->hasMany('App\Modals\Comment', 'request_task_id','id');
    }

    public function histories()
    {
        return $this->hasMany('App\Modals\History', 'request_task_id','id');
    }

    public function taskTags()
    {
        return $this->hasMany('App\Modals\TaskTag', 'request_task_id','id');
    }
}
