<?php

namespace App\Services\Notification;

use LaravelFCM\Facades\FCM;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use LaravelFCM\Message\Topics;


/**
 * @author Fazlul Kabir Shohag <shohag.fks@gmail.com>
 */

class PushNotificatonService
{
    private $title;
    private $body;
    private $data;
    private $option;
    private $notification;
    private $dataBuilder;
    private $boardcastTime;

    /**
     * @param String $title
     * @return 
     */
    public function __construct($title = '')
    {
        $this->title = $title;
        $this->boardcastTime = 0;
    }

    /**
     * @param String $title
     * @return 
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @param null
     * @return String $title
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param String $body
     * @return 
     */
    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    /**
     * @param null
     * @return String $body
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param String $data
     * @return 
     */
    public function setData($data): void
    {
        $this->data = $data;
    }

    /**
     * @param null
     * @return String $data
     */
    public function getData(): string
    {
        return $this->data;
    }

    /**
     * @param String $data
     * @return 
     */
    public function setTimeToLive($time): void
    {
        $this->boardcastTime = $time;
    }

    /**
     * @param null
     * @return String $data
     */
    public function getTimeToLive(): string
    {
        return $this->boardcastTime;
    }

    /**
     * Configuration set for push notification 
     * @param null
     * @return null 
     */
    private function configSet(): void
    {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60 * $this->boardcastTime);

        $notificationBuilder = new PayloadNotificationBuilder($this->title);
        $notificationBuilder->setBody($this->body)
            ->setSound('default');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['a_data' => $this->data]);

        $this->option = $optionBuilder->build();
        // $option = null;
        $this->notification = $notificationBuilder->build();
        $this->dataBuilder = $dataBuilder->build();
    }

    /**
     * @param Object $downStream
     * @return null
     */
    private function downstreamResponseReset($downstreamResponse): void
    {
        $downstreamResponse->tokensToDelete();
        $downstreamResponse->tokensToModify();
        $downstreamResponse->tokensToRetry();
        $downstreamResponse->tokensWithError();
    }

    /**
     * @param String $token
     * @return
     */
    public function send($token): void
    {
        $this->configSet();
        $downstreamResponse = FCM::sendTo($token, $this->option, $this->notification, $this->dataBuilder);
        $this->downstreamResponseReset($downstreamResponse);
    }

    /**
     * @param null
     * @return nul
     */
    public function sendTopic(string $news): void
    {
        $notificationBuilder = new PayloadNotificationBuilder($this->title);
        $notificationBuilder->setBody($this->body)
            ->setSound('default');
        $notification = $notificationBuilder->build();

        $topic = new Topics();
        $topic->topic($news);

        $topicResponse = FCM::sendToTopic($topic, null, $notification, null);

        $topicResponse->isSuccess();
        $topicResponse->shouldRetry();
        $topicResponse->error();
    }
}
