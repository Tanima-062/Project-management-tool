<?php

namespace App\Modals;

use Illuminate\Database\Eloquent\Model;

class Requests extends Model
{
    public function user()
    {
        return $this->belongsTo('App\Modals\User', 'request_user_id', 'id');
    }
}
