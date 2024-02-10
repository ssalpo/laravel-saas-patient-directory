<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roleAdmin = Role::create(['name' => 'admin', 'readable_name' => 'Администратор системы']);

        $permissionManageRoles = Permission::create(['name' => 'manage_roles', 'readable_name' => 'Управлять ролями']);
        $permissionManageUsers = Permission::create(['name' => 'manage_users', 'readable_name' => 'Управлять пользователями']);

        $roleAdmin->syncPermissions([
            $permissionManageRoles,
            $permissionManageUsers,
        ]);
    }
}
