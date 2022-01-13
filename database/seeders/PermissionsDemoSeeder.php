<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class PermissionsDemoSeeder extends Seeder
{
    /**
     * Create the initial roles and permissions.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'edit book']);
        Permission::create(['name' => 'delete book']);
        Permission::create(['name' => 'update book']);
        Permission::create(['name' => 'create book']);
        Permission::create(['name' => 'read book']);

        // create roles and assign existing permissions
        $role1 = Role::create(['name' => 'Super-Admin']);
        $role1->givePermissionTo('edit book');
        $role1->givePermissionTo('delete book');
        $role1->givePermissionTo('update book');
        $role1->givePermissionTo('create book');
        $role1->givePermissionTo('read book');

        $role2 = Role::create(['name' => 'admin']);
        $role2->givePermissionTo('create book');
        $role2->givePermissionTo('delete book');
        $role2->givePermissionTo('read book');

        $role3 = Role::create(['name' => 'guest']);
        $role3->givePermissionTo('read book');
        // gets all permissions via Gate::before rule; see AuthServiceProvider

        // create demo users
        $user = User::find(1);
        $user->assignRole($role1);

        $user = User::find(5);
        $user->assignRole($role2);

        $user = User::find(4);
        $user->assignRole($role3);
    }
}