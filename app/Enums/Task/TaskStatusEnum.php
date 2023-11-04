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

class TaskStatusEnum extends BaseEnum {
    private const Active = 1;
    private const Complete = 2;
    private const Inactive = 3;

    public static function getKeyByValue($value) {
        if($value == 1) return 'Active';
        else if ($value == 2) return 'Complete';
        return 'Inactive';
    }
}