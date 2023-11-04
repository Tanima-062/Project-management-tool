<?php

namespace App\Modals;

use Illuminate\Database\Eloquent\Model;

class MemberTask extends Model
{
    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
            $model->updated_at = $model->freshTimestamp();
        });
    }

    public function userWithTrashed()
    {
        return $this->belongsTo('App\Modals\User', 'user_id', 'id')->withTrashed();
    }
    
    public function task()
    {
        return $this->belongsTo('App\Modals\Task', 'task_id','id');
    }
    public function team()
    {
        return $this->belongsTo('App\Modals\Team', 'team_id','id');
    }
    public function user()
    {
        return $this->belongsTo('App\Modals\User', 'member_user_id', 'id');
    }
}
