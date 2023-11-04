<?php

namespace App\Modals;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    public function userPrograms()
    {
        return $this->hasMany('App\Modals\UserProgram', 'program_id','id');
    }
}
