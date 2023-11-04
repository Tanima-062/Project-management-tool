<?php

namespace App\Modals;

use App\Models\Base\BaseModel;

class Member extends BaseModel
{
    public function team()
    {
        return $this->belongsTo('App\Modals\Team', 'team_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo('App\Modals\User', 'user_id', 'id');
    }

}
