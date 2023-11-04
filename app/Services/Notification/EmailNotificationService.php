<?php

/**
 * @author [Fazlul Kabir Shohag]
 * @email [shohag.fks@mail.com]
 * @create date 2021-02-03 14:24:18
 * @modify date 2021-02-03 14:24:18
 * @desc [description]
 */

namespace App\Services\Notification;


class EmailNotificationService extends BaseNotificationService
{
    public function __construct($code, $type, $to) {
        parent::__construct($code, $type, $to);
    }
}
