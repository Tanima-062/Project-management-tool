<?php

namespace App\Modals;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name'];

    public function taskTags()
    {
        return $this->hasMany('App\Modals\TaskTag', 'tag_id','id');
    }
}
