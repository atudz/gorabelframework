<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

         $this->call(UserSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(NavigationSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(RoleToNavSeeder::class);
        $this->call(UserToRoleSeeder::class);
        $this->call(FeatureSeeder::class);
        $this->call(FeatureToRoleSeeder::class);  

        Model::reguard();
    }
}
