<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Roles
        $superAdminRole         = Role::create(['name' => 'Super_Admin', 'guard_name' => 'api']);
        $adminRole              = Role::create(['name' => 'Admin', 'guard_name' => 'api']);
        $employeeRole           = Role::create(['name' => 'Employee', 'guard_name' => 'api']);
        $userRole               = Role::create(['name' => 'User', 'guard_name' => 'api']);

        // Permissions
        $createPermission       = Permission::create(['name' => 'create', 'guard_name' => 'api']);
        $readPermission         = Permission::create(['name' => 'read', 'guard_name' => 'api']);
        $updatePermission       = Permission::create(['name' => 'update', 'guard_name' => 'api']);
        $deletePermission       = Permission::create(['name' => 'delete', 'guard_name' => 'api']);
        $createAdminPermission  = Permission::create(['name' => 'create.admins', 'guard_name' => 'api']);

        // Assign permissions to super_admin role
        $superAdminRole->givePermissionTo($createPermission);
        $superAdminRole->givePermissionTo($readPermission);
        $superAdminRole->givePermissionTo($updatePermission);
        $superAdminRole->givePermissionTo($deletePermission);
        $superAdminRole->givePermissionTo($createAdminPermission);

        // Assign permissions to admin role
        $adminRole->givePermissionTo($createPermission);
        $adminRole->givePermissionTo($readPermission);
        $adminRole->givePermissionTo($updatePermission);
        $adminRole->givePermissionTo($deletePermission);
        $adminRole->givePermissionTo($createAdminPermission);

        // Assign permissions to employee role
        $employeeRole->givePermissionTo($createPermission);
        $employeeRole->givePermissionTo($readPermission);
        $employeeRole->givePermissionTo($updatePermission);
        $employeeRole->givePermissionTo($deletePermission);

        // Assign permissions to user role
        $adminRole->givePermissionTo($readPermission);
        $adminRole->givePermissionTo($updatePermission);
    }
}
