<?php

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $today = new DateTime();
        
        DB::table('role')->insert([
    			['name'=>'Administrator','created_at' => $today,'value'=>'role_admin', 'updated_at' => $today,'system'=>1],
                ['name'=>'Customer','created_at' => $today,'value'=>'role_customer','updated_at' => $today,'system'=>1],
    	]);
    }
}
