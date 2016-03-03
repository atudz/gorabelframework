<?php

use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    { 
    	 $today = new DateTime();
         $permissions = [
         		['name'=>'Manage User','value'=>'permission_manage_user','created_at'=>$today,'updated_at'=>$today,'category'=>'admin'],
         		['name'=>'Admin Control','value'=>'permission_admin_control','created_at'=>$today,'updated_at'=>$today,'category'=>'admin'],
        ];
    	DB::table('permission')->insert($permissions);
    }
}
