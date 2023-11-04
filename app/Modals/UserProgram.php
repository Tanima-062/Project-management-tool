<?php

namespace App\Modals;

use Illuminate\Database\Eloquent\Model;

class UserProgram extends Model
{
    protected $fillable = ['user_id', 'program_id', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo('App\Modals\User', 'user_id','id');
    }
    public function program()
    {
        return $this->belongsTo('App\Modals\Program', 'program_id','id');
    }
}
