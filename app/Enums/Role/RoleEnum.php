<?php


namespace App\Enums\Role;

use App\Enums\Base\BaseEnum;

/**
 * @author [Fazlul Kabir Shohag]
 * @email [shohag.fks@mail.com]
 * @create date 2021-01-31 13:10:25
 * @modify date 2021-01-31 13:10:25
 * @desc [description]
 */

class RoleEnum extends BaseEnum {
    private const Admin = 1;
    private const TeamLead = 2;
    private const Member = 3;
}