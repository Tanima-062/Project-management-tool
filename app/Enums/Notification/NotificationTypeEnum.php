<?php


namespace App\Enums\Notification;

use App\Enums\Base\BaseEnum;

/**
 * @author [Fazlul Kabir Shohag]
 * @email [shohag.fks@mail.com]
 * @create date 2021-01-31 13:10:25
 * @modify date 2021-01-31 13:10:25
 * @desc [description]
 */

class NotificationTypeEnum extends BaseEnum {
    private const Email = "email";
    private const Sms = "sms";
    private const Push = "push";
    private const Internal = 'internal';
}