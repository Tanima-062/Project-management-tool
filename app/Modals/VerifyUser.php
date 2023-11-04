<?php

namespace App\Modals;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VerifyUser extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'token',
        'user_id',
    ];
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
