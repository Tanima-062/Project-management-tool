<?php

namespace App\Modals;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    public function task()
    {
        return $this->belongsTo('App\Modals\Task', 'task_id','id');
    }
    public function user()
    {
        return $this->belongsTo('App\Modals\User', 'user_id','id');
    }
    public function comment()
    {
        return $this->belongsTo('App\Modals\Comment', 'comment_id','id');
    }
    public function attachments()
    {
        return $this->hasMany('App\Modals\Attachment', 'reply_id','id');
    }
}
