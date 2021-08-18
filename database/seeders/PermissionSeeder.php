<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use \Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $administrator = Role::create([
            'name' => 'administrator',
        ]);

        $administrator_permissions = [
            'edit_public_phrases',
            'read_public_phrases',
            'delete_public_phrases',
            'edit_users',
            'view_users',
            'delete_users',
            'resend_activation_email',
            'login_as_users',
        ];

        $user = Role::create([
            'name' => 'user',
        ]);

        $user_permissions = [
            'has_access',
        ];

        $banned = Role::create([
            'name' => 'banned',
        ]);

        $not_subscribed = Role::create([
            'name' => 'not_subscribed',
        ]);

        foreach ( $administrator_permissions as $permission ) {
            $model = Permission::create([
                'name' => $permission,
            ]);

            $administrator->givePermissionTo( $model );
        }

        foreach ( $user_permissions as $permission ) {
            $model = Permission::create([
                'name' => $permission,
            ]);

            $user->givePermissionTo( $model );
        }
    }
}
