<?php

namespace App\Imports;

use App\Modals\User;
use Maatwebsite\Excel\Concerns\ToModel;

class UsersImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new User([
            'name'     => $row['name'],
            'pin_number' => $row['pin_number'],
            'designation' => $row['designation'],
            'program' => $row['program'],
            'email' => $row['email'],
            'phone_number'    => $row['phone_number'],
            'role' => $row['role'],
            'status' => $row['status'],
            'unit' => $row['unit'],
            'supervisor' => $row['supervisor'],
            'password' => \Hash::make($row['password']),
        ]);
    }
}
