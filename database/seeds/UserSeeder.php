<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;
use App\Permission;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $mainadmin = Role::where('slug','main-admin')->first();
        $moderator = Role::where('slug', 'moderator')->first();
        $createTasks = Permission::where('slug','create-tasks')->first();
        $manageUsers = Permission::where('slug','manage-users')->first();

        $user1 = new User();
        $user1->name = 'Main admin';
        $user1->email = 'admin@admin.kz';
        $user1->password = bcrypt('secret');
        $user1->save();
        $user1->roles()->attach($mainadmin);
        $user1->permissions()->attach($createTasks);


        $user2 = new User();
        $user2->name = 'Moderator';
        $user2->email = 'moderator@moderator.kz';
        $user2->password = bcrypt('secret');
        $user2->save();
        $user2->roles()->attach($moderator);
        $user2->permissions()->attach($manageUsers);
    }
}
