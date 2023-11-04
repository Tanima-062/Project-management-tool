<?php

/**
 * @author Md.Shohag <Shohag.fks@gmail.com>
 */

namespace App\Models\Logs;

use App\Engine\Interfaces\VisionImplementable;
use App\Models\Base\BaseModel;
use App\User;
use Illuminate\Support\Facades\Auth;


/**
 * @author [Fazlul Kabir Shohag]
 * @email [shohag.fks@gmail.com]
 * @create date 2021-02-25 14:42:15
 * @modify date 2021-02-25 14:42:15
 * @desc [description]
 */


class Log extends BaseModel implements VisionImplementable
{
    /**
     * @var string $table
     */
    const LOG = 'log';
    const STORE = 'store';
    const CHANGE = 'change';
    const DELETE = 'delete';
    /**
     * result
     */
    const SUCCESS = 'success';
    const NEUTRAL = 'neutral';
    const FAILURE = 'failure';
    const PAYROLL = 'payroll';
    const PMS = 'pms';
    const PROMOTIONANDSHFING = 'PROMOTIONANDSHFING';
    const LOGIN = 'login';

    protected $table = 'logs';
    /**
     * @var array $guarded
     */
    protected $guarded = ['id'];

    public function user() {
        return $this->belongsTo(User::class, 'userId');
    }

    public function serializerFields()
    {
        return ['id', 'userId', 'user__name', 'message', 'exception', 'origin', 'type', 'result', 'level',
            'token', 'ip', 'user_agent', 'session'];
    }

    public static function postserializerFields()
    {
        return ['userId', 'user', 'message', 'exception', 'origin', 'type', 'result', 'level',
            'token', 'ip', 'user_agent', 'session'];
    }

    static public function fieldsValidator()
    {
        return [
            'userId' => 'required',
        ];
    }

    function __construct()
    {
        parent::__construct($this);
    }

    public function exportTableFields()
    {
        return ['id', 'userId->user id', 'user__name->user name', 'message', 'exception', 'origin', 'type', 'result', 'level',
            'token', 'ip', 'user_agent->user agent', 'session'];
    }

    public static function setLog($type, $message, $exception = '', $current_user = null) {
        $current_user = $current_user ? $current_user : Auth::user()->id;
        $newLog = new Log();
        $newLog->message = $message;
        $newLog->userId = $current_user;
        $newLog->type = $type;
        $newLog->exception = $exception;
        $newLog->origin = request()->headers->get('origin');
        $newLog->ip = request()->server('REMOTE_ADDR');
        $newLog->user_agent = request()->server('HTTP_USER_AGENT');
        $newLog->save();
    }
}
