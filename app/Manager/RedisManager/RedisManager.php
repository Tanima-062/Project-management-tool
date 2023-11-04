<?php

namespace App\Manager\RedisManager;
use App\Utility\DynamicMenuCache;
use App\Utility\GenericManager;
use App\Utility\UserManager;

class RedisManager
{
    use UserManager;

    public static function Generic() {
        return new GenericManager();
    }

}
