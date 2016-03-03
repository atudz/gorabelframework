<?php

use Illuminate\Database\Seeder;

class RoleToNavSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
   		 // fetch admin ID
    	$adminID = DB::table('role')->where(['name'=>'Administrator'])->value('id');
    	
    	if($adminID)
    	{
        	//fetch all navigations
        	$navs = [
                'Dashboard',
                'Admin Control',
                'Roles',
        		'Manage Admin'	
            ];
        	$mappings = [];
        	$today = new DateTime();
        	foreach($navs as $nav)
        	{
	        	if($menuId = DB::table('navigation')->where('name','=',$nav)->value('id'))
	        	{
	    			$mappings[] = ['navigation_id'=>$menuId,'role_id'=>$adminID,'created_at' => $today,'updated_at' => $today];
	    		}
        	}
    		DB::table('role_to_nav')->insert($mappings);
    	}
    }
}
