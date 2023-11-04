<?php

namespace App\Modals;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    public function task()
    {
        return $this->belongsTo('App\Modals\Task', 'task_id','id');
    }
    public function comment()
    {
        return $this->belongsTo('App\Modals\Comment', 'comment_id','id');
    }
    public function reply()
    {
        return $this->belongsTo('App\Modals\Reply', 'reply_id','id');
    }
}
