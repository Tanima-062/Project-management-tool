<?php

namespace App\Modals;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public function task()
    {
        return $this->belongsTo('App\Modals\Task', 'task_id','id');
    }
    public function user()
    {
        return $this->belongsTo('App\Modals\User', 'user_id','id');
    }
    public function replies()
    {
        return $this->hasMany('App\Modals\Reply', 'comment_id','id');
    }
    public function attachments()
    {
        return $this->hasMany('App\Modals\Attachment', 'comment_id','id');
    }
}
