<?php

namespace App\Exports;

use Spatie\Permission\Models\Role;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class RolesExport implements FromCollection, WithMapping, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Role::all();
    }

    public function headings(): array
    {
        return [
            'Role',
            'Permissions',
            'Status',
            'Created At',
            'Updated At'
        ] ;
    }

    public function map($row): array
    {
        return [
            $row->name,
            $row->permissions,
            $row->status,
            Carbon::parse($row->created_at)->toFormattedDateString(),
            Carbon::parse($row->updated_at)->toFormattedDateString()
        ];
    }

    public function prepareRows($rows): array
    {
        return array_map(function ($role) {
           $permission = '';
           if($role->permissions){
               foreach ($role->permissions as $perm){
                   $permission .= $perm->displayName . ', ';
               }
           }else{
               $permission = '';
           }
           if($role->status == '1'){
               $role->status = 'Active';
           }
            if($role->status == '0'){
                $role->status = 'Inactive';
            }
            $role->permissions = $permission;
           return $role;
        }, $rows);
    }
}
