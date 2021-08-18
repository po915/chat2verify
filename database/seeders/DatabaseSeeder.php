<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
/*        User::factory()->withPersonalTeam()->createOne([
            'name' => 'Jarrod N.',
            'email' => 'jarrod@noonan.co',
            'password' => Hash::make('demo123'),
        ]);*/

        $this->call( PermissionSeeder::class );
        // \App\Models\User::factory(10)->create();
    }
}
