<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Create Roles

        // DB::table('roles')->delete();
        // DB::table('permissions')->delete();

        $roleSuperAdmin = Role::create(['name' => 'Super Admin','status' => 1]);
        //Permission List as array
        $permissions = [

            //User Permissions
            [
                'group_name' => 'User Management',
                'permissions' => [
                    ['name' =>'user.create', 'displayName' => 'User Create'],
                    ['name' => 'user.view', 'displayName' => 'User View'],
                    ['name' => 'user.edit','displayName' => 'User Edit'],
                    ['name' => 'user.delete','displayName' => 'User Delete'] ,

                ]
            ],

            //Role Permissions
            [
                'group_name' => 'Role Management',
                'permissions' => [
                    ['name' =>'role.create', 'displayName' => 'Role Create'],
                    ['name' => 'role.view', 'displayName' => 'Role View'],
                    ['name' => 'role.edit','displayName' => 'Role Edit'],
                    ['name' => 'role.delete','displayName' => 'Role Delete'] ,
                ]
            ],

            //Analytical Dashboard
            [
                'group_name' => 'Analytical Dashboard',
                'permissions' => [
                    ['name' =>'task.analytics.show', 'displayName' => 'Task Analytics Show'],
                ]
            ],

            //Report
            [
                'group_name' => 'Report',
                'permissions' => [
                    ['name' =>'report.show', 'displayName' => 'Report Show'],
                ]
            ],

            [
                'group_name' => 'Tag Management',
                'permissions' => [
                    ['name' =>'tag.create', 'displayName' => 'Tag Create'],
                    ['name' => 'tag.view', 'displayName' => 'Tag View'],
                    ['name' => 'tag.edit','displayName' => 'Tag Edit'],
                    ['name' => 'tag.delete','displayName' => 'Tag Delete'] ,

                ]
            ],

            [
                'group_name' => 'Team Management',
                'permissions' => [
                    ['name' =>'team.create', 'displayName' => 'Team Create'],
                    ['name' => 'team.view', 'displayName' => 'Team View'],
                    ['name' => 'team.edit','displayName' => 'Team Edit'],
                    ['name' => 'team.delete','displayName' => 'Team Delete'] ,

                ]
            ],

            [
                'group_name' => 'Department Management',
                'permissions' => [
                    ['name' =>'department.create', 'displayName' => 'Department Create'],
                    ['name' => 'department.view', 'displayName' => 'Department View'],
                    ['name' => 'department.edit','displayName' => 'Department Edit'],
                    ['name' => 'department.delete','displayName' => 'Department Delete'] ,

                ]
            ],

            [
                'group_name' => 'Company Management',
                'permissions' => [
                    ['name' =>'company.create', 'displayName' => 'Company Create'],
                    ['name' => 'company.view', 'displayName' => 'Company View'],
                    ['name' => 'company.edit','displayName' => 'Company Edit'],
                    ['name' => 'company.delete','displayName' => 'Company Delete'] ,

                ]
            ],


            //Notification Module
            // [
            //     'group_name' => 'Notification Module',
            //     'permissions' => [
            //         ['name' =>'notification.module.show', 'displayName' => 'Notification Module Show'],
            //     ]
            // ],
        ];

        //Create and Assign Permissions

        for($i=0; $i < count($permissions) ; $i++){
            $permissionGroup = $permissions[$i]['group_name'];
            for($j=0; $j < count($permissions[$i]['permissions']) ; $j++) {
                //Create Permission
                $permission = Permission::create(['name' => $permissions[$i]['permissions'][$j]['name'], 'displayName' => $permissions[$i]['permissions'][$j]['displayName'], 'group_name' => $permissionGroup]);
                $roleSuperAdmin->givePermissionTo($permission);
                $permission->assignRole($roleSuperAdmin);
            }
        }

    }
}
