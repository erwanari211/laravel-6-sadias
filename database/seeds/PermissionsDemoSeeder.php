<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use App\User;

class PermissionsDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'edit articles']);
        Permission::create(['name' => 'delete articles']);
        Permission::create(['name' => 'publish articles']);
        Permission::create(['name' => 'unpublish articles']);

        // create roles and assign existing permissions
        $role1 = Role::create(['name' => 'p-writer']);
        $role1->givePermissionTo('edit articles');
        $role1->givePermissionTo('delete articles');

        $role2 = Role::create(['name' => 'p-admin']);
        $role2->givePermissionTo('publish articles');
        $role2->givePermissionTo('unpublish articles');

        $role3 = Role::create(['name' => 'p-super-admin']);
        // gets all permissions via Gate::before rule; see AuthServiceProvider

        // create demo users
        $user = factory(User::class)->create([
            'name' => 'Example User',
            'email' => 'p_test@example.com',
        ]);
        $user->assignRole($role1);

        $user = factory(User::class)->create([
            'name' => 'Example Admin User',
            'email' => 'p_admin@example.com',
        ]);
        $user->assignRole($role2);

        $user = factory(User::class)->create([
            'name' => 'Example Super-Admin User',
            'email' => 'p_superadmin@example.com',
        ]);
        $user->assignRole($role3);

    }
}
