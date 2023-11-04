<?php

namespace App\Http\Controllers\Logs;

use App\Http\Controllers\Base\BaseController;
use App\Models\Logs\Log;


/**
 * @author [Fazlul Kabir Shohag]
 * @email [shohag.fks@gmail.com]
 * @create date 2021-02-25 14:42:15
 * @modify date 2021-02-25 14:42:15
 * @desc [description]
 */


class LogController extends BaseController
{
    function __construct(Log $log)
    {
        $this->entityInstance = $log;
        parent::__construct();
    }
}
