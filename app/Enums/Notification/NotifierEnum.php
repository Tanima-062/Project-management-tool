<?php

namespace App\Enums\Notification;

use App\Enums\Base\BaseEnum;

class NotifierEnum extends BaseEnum
{
    private const ChannelName = 'tmp_public';
    private const GeneralEvent = 'general_notification';
    private const TeamPostfix = 'tmp_team_notifitcation';
    private const UserPostfix = 'user_notification';
}
