<?php

namespace App\Modals;

use Illuminate\Database\Eloquent\Model;

class TemporaryFile extends Model
{
    protected $fillable = ['folder', 'file_name'];
}
