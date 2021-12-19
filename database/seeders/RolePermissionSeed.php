<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roleAdmin = Role::updateOrCreate([
            'name' => 'Administrator'
        ],[
            'name' => 'Administrator'
        ]);

        $roleUser = Role::updateOrCreate([
            'name' => 'User'
        ],[
            'name' => 'User'
        ]);

        $roleBuruh = Role::updateOrCreate([
            'name' => 'Buruh'
        ],[
            'name' => 'Buruh'
        ]);

        $permissions = [
            'Read Role & Permission',
            'Create Role & Permission',
            'Update Role & Permission',
            'Delete Role & Permission',
            'Read Todo Payments',
            'Detail Todo Payments',
            'Create Todo Payments',
            'Update Todo Payments',
            'Delete Todo Payments',
            'Read Users',
            'Create Users',
            'Update Users',
            'Delete Users',
        ];

        foreach ($permissions as $value) {
            Permission::updateOrCreate([
                'name' => $value,
            ],[
                'name' => $value
            ]);
        }


        $roleAdmin->syncPermissions($permissions);
        $roleUser->syncPermissions([
            'Read Todo Payments',
            'Detail Todo Payments',
            'Create Todo Payments',
        ]);
    }
}
