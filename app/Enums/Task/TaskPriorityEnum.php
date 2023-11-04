<?php


namespace App\Enums\Task;

use App\Enums\Base\BaseEnum;

/**
 * @author [Fazlul Kabir Shohag]
 * @email [shohag.fks@mail.com]
 * @create date 2021-01-31 13:10:25
 * @modify date 2021-01-31 13:10:25
 * @desc [description]
 */

class TaskPriorityEnum extends BaseEnum {
    private const High = 1;
    private const Medium = 2;
    private const Low = 3;

    public static function getKeyByValue($value) {
        if($value == 1) return 'High';
        else if ($value == 2) return 'Medium';
        return 'Low';
    }
}