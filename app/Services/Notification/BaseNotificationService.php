<?php

/**
 * @author [Fazlul Kabir Shohag]
 * @email [shohag.fks@mail.com]
 * @create date 2021-02-03 14:24:18
 * @modify date 2021-02-03 14:24:18
 * @desc [description]
 */

namespace App\Services\Notification;

use App\Jobs\EmailJob;
use App\Models\Notification\NotificationTemplate;
use App\Utility\Services\Email\EmailProperty;
use App\Utility\Services\Email\EmailRecipient;

class BaseNotificationService
{

    private $templateData = '';
    public function __construct($code, $type, $to)
    {
        $template =  $this->getTemplate($type, $code);
        $subject = $template ? $template->subject : '';
        $this->subject = $subject;
        $this->templateData = $template->body;
        $this->to = $to;
    }

    public function replaceTempateVariable($data) {
        $this->templateData = $this->templateData($data, $this->templateData);
        $this->subject = $this->templateData($data, $this->subject);
    }

    public function sendEmail()
    {   
        $this->mailSend($this->to, $this->templateData, $this->subject, $this->subject);
    }

    protected function templateData($emailDatas, $templateData)
    {

        foreach ($emailDatas as $key => $value) {
            $templateData = str_replace("{{" . $key . "}}", $value, $templateData);
        }
        return $templateData;
    }

    protected function getTemplate($notificationType, $code)
    {

        $result = NotificationTemplate::where('type', $notificationType)->where('code', $code)->first();
        return $result;
    }

    protected function spaceRemoveForSms($templateData)
    {
        return  str_replace(" ", "+", $templateData);
    }


    protected function send($details)
    {
        EmailJob::dispatchNow($details);
    }

    protected function sendOnQueue($details)
    {
        EmailJob::dispatch($details);
    }

    protected function mailSend($to, $template, $title, $subject, $file = null, $cc = null)
    {
        $fromAddress = "taskmanager@brac.net";
        
        $emailContent = new EmailProperty();
        $emailContent->jobContentType = 'html';
        $emailContent->fromAddress = $fromAddress;
        $emailContent->udValue1 = 'TaskManager';
        $emailContent->requester = 'TaskManager';
        $emailContent->toText = $to;
        $emailContent->bccAddress = '';

        $emailContent->subject = $subject;
        $emailContent->body = $template;
        $emailContent->jobRecipients[0] = new EmailRecipient;
        $emailContent->jobRecipients[0]->recipientEmail = $to;

        $emailContent = array('jobs' => $emailContent);
        $this->send($emailContent);
    }
}
