<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'create-event']);
        Permission::create(['name' => 'create-jogosultsag']);
        Permission::create(['name' => 'view-loggs']);

        $superadmin = Role::create(['name' => 'super-admin']);
        $superadmin->givePermissionTo('create-event');
        $superadmin->givePermissionTo('create-jogosultsag');
        $superadmin->givePermissionTo('view-loggs');

        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo('create-event');
        $admin->givePermissionTo('create-jogosultsag');

        $user = Role::create(['name' => 'user']);
        $user->givePermissionTo('create-jogosultsag');
        $user->givePermissionTo('view-loggs');


        $sadmin           = new User();
        $sadmin->name     = 'Admin';
        $sadmin->email    = 'admin@admin.com';
        $sadmin->password = bcrypt('admin');
        $sadmin->save();
        $sadmin->assignRole($superadmin);

        $adminu1 = User::create([
            'name'     => 'Admin1',
            'email'    => 'admin@group1.com',
            'password' => bcrypt('admin'),
        ]);
        $adminu1->assignRole($admin);

        $adminu2 = User::create([
            'name'     => 'Admin2',
            'email'    => 'admin@grup3.com',
            'password' => bcrypt('admin'),
        ]);
        $adminu2->assignRole($admin);

        $useru1 = User::create([
            'name'     => 'User1',
            'email'    => 'user@group1.com',
            'password' => bcrypt('user'),
        ]);
        $useru1->assignRole($user);
    }
}
