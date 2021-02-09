<?php

use Illuminate\Database\Seeder;
use App\Role;
class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $manager = new Role();
      $manager->name = 'Main Admin';
      $manager->slug = 'main-admin';
      $manager->save();

      $developer = new Role();
      $developer->name = 'Moderator';
      $developer->slug = 'moderator';
      $developer->save();
    }
}
