<?php

namespace App\Models\Notification;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @author [Fazlul Kabir Shohag]
 * @email [shohag.fks@mail.com]
 * @create date 2021-01-31 13:00:33
 * @modify date 2021-01-31 13:00:33
 * @desc [description]
 */

class NotificationTemplate extends Model
{
    protected $table = 'notifications';
    use SoftDeletes;

    protected $fillable = [
        'code', 'type', 'subject', 'body'
    ];
}
