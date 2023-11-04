<?php

namespace App\Utility\Services\Email;

class EmailRecipient 
{
    public $failCount; // int
    public $image; // base64Binary
    public $job; // jobs
    public $jobDetailId; // long
    public $recipientEmail; // string
    public $sent; // boolean
    public $sentDate; // dateTime
    public $toText; // string
}

