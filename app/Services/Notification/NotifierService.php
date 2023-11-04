<?php

namespace App\Services\Notification;

use App\Enums\Notification\NotificationCodeEnum;
use App\Enums\Notification\NotificationTypeEnum;
use App\Enums\Notification\NotifierEnum;
use App\Events\NotificationEvent;
use App\Models\Notification\NotificationTemplate;
use App\Models\Notification\Notifier;


/**
 * @author Fazlul Kabir Shohag <shohag.fks@gmail.com>
 */

class NotifierService
{
    private $channel;
    private $event;
    private $message;
    private $image = null;
    private $recipient_id;
    private $task_id;
    private $task_url;

    public function __construct($channel, $event, $message, $recipient_id, $task_id, $task_url)
    {
        $this->channel = $channel;
        $this->event = $event;
        $this->message = $message;
        $this->recipient_id = $recipient_id;
        $this->task_id = $task_id;
        $this->taskUrl = $task_url;
    }

    public function getChannel()
    {
        return $this->channel;
    }

    public function getEvent()
    {
        return $this->event;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function getTaskUrl()
    {
        return $this->taskUrl;
    }

    public function setChannel($channel)
    {
        $this->channel = $channel;
    }

    public function setEvent($event)
    {
        $this->event = $event;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function setTaskUrl($url)
    {
        $this->taskUrl = $url;
    }

    public function send()
    {
        $array = [
            'data' => $this->message
        ];
        Notifier::storeNotifier($this->channel, $this->event, $this->message, $this->recipient_id, $this->task_id, $this->image, $this->taskUrl);
        event(new NotificationEvent($this->channel, $this->event, $array['data']));
    }

    public static function getTeamEvent($team_id)
    {
        return $team_id . '_' . NotifierEnum::TeamPostfix()->getValue();
    }

    public static function getUserEvent($user_id)
    {
        return $user_id . '_' . NotifierEnum::UserPostfix()->getValue();
    }

    protected static function getTemplate($notificationType, $code)
    {

        $result = NotificationTemplate::where('type', $notificationType)->where('code', $code)->first();
        return $result;
    }

    public static function sendIndividualPerson($user_id, $task_id,$code, $task_url, $variableData = [])
    {
        $message = self::getTemplate(NotificationTypeEnum::Internal()->getValue(), $code);
            $notifier = new NotifierService(
                NotifierEnum::ChannelName()->getValue(),
                NotifierService::getUserEvent($user_id),
                self::replaceBodyVariable($variableData, $message ? $message->body : ''),
                $user_id,
                $task_id,
                $task_url
            );
        $notifier->send();
    }

    private static function replaceBodyVariable($emailDatas, $templateData)
    {
        foreach ($emailDatas as $key => $value) {
            $templateData = str_replace("{{" . $key . "}}", $value, $templateData);
        }
        return $templateData;
    }

    public static function sendTeamPerson($team_id, $message, $variableData = [])
    {
        $notifier = new NotifierService(NotifierEnum::ChannelName()->getValue(), NotifierService::getTeamEvent($team_id), self::replaceBodyVariable($variableData, $message));
        $notifier->send();
    }
}
