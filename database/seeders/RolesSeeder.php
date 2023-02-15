<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        $roleAdmin = Role::create(['name' => 'admin', 'readable_name' => 'Администратор']);
        $roleResident = Role::create(['name' => 'resident', 'readable_name' => 'Ординатор']);
        $roleDoctor = Role::create(['name' => 'doctor', 'readable_name' => 'Доктор']);

        $permissionReadUsers = Permission::create(['name' => 'read_users']);
        $permissionEditUsers = Permission::create(['name' => 'edit_users']);
        $permissionCreateUsers = Permission::create(['name' => 'create_users']);
        $permissionDeleteUsers = Permission::create(['name' => 'delete_users']);

        $permissionsManagerUsers = [
            $permissionReadUsers,
            $permissionEditUsers,
            $permissionCreateUsers,
            $permissionDeleteUsers,
        ];

        $permissionReadPatients = Permission::create(['name' => 'read_all_patients']);
        $permissionSelectDoctorPatients = Permission::create(['name' => 'select_doctor_patients']);
        $permissionEditPatients = Permission::create(['name' => 'edit_patients']);
        $permissionCreatePatients = Permission::create(['name' => 'create_patients']);
        $permissionDeletePatients = Permission::create(['name' => 'delete_patients']);

        $permissionsManagerPatients = [
            $permissionReadPatients,
            $permissionEditPatients,
            $permissionCreatePatients,
            $permissionDeletePatients
        ];

        $permissionReadDoctors = Permission::create(['name' => 'read_doctors']);
        $permissionEditDoctors = Permission::create(['name' => 'edit_doctors']);
        $permissionCreateDoctors = Permission::create(['name' => 'create_doctors']);
        $permissionDeleteDoctors = Permission::create(['name' => 'delete_doctors']);

        $permissionsManagerDoctors = [
            $permissionReadDoctors,
            $permissionEditDoctors,
            $permissionCreateDoctors,
            $permissionDeleteDoctors
        ];

        $permissionAddReport = Permission::create(['name' => 'add report']);

        $roleAdmin->syncPermissions([
            ...$permissionsManagerUsers,
            ...$permissionsManagerPatients,
            $permissionSelectDoctorPatients,
            ...$permissionsManagerDoctors,
            $permissionAddReport
        ]);

        $roleDoctor->syncPermissions([
            ...$permissionsManagerPatients
        ]);
    }
}
