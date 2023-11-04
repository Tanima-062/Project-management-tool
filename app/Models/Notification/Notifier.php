<?php

namespace App\Models\Notification;

use App\Models\Base\BaseModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

/**
 * @author Md.Shohag <Shohag.fks@gmail.com>
 */

class Notifier extends BaseModel
{
    protected $table = 'notifier';

    public function __construct()
    {
        parent::__construct($this);
    }

    public function SerializerFields()
    {
        return ['id', 'channel', 'event', 'image', 'message'];
    }

    static public function PostSerializerFields()
    {
        return ['id', 'channel', 'event', 'image', 'message'];
    }

    static public function FieldsValidator()
    {
        return [
            'channel' => 'required',
            'event' => 'required',
            'message' => 'required',
        ];
    }

    public static function storeNotifier($channel, $event, $message, $recipient_id, $task_id, $image = null, $taskUrl= null) {
        $newNotifier = new Notifier();
        $newNotifier->channel = $channel;
        $newNotifier->event = $event;
        $newNotifier->message = $message;
        $newNotifier->image = $image;
        $newNotifier->recipient_id = $recipient_id;
        $newNotifier->task_id = $task_id;
        $newNotifier->task_url = $taskUrl;
        $newNotifier->save();
    }

    public static function getNotifier($id){
        $auth_user_id = Auth::user()['id'];
        $event = (string)$id.'_user_notification';

        $notifications = DB::table('notifier')
                            ->where('event', '=', $event)
                            ->where('is_read','=',0)
                            ->where('recipient_id','=', $auth_user_id)
                            ->orderBy('id', 'DESC')
                            ->take(50)
                            ->get();

        return $notifications;
    }
}
