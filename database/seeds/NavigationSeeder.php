<?php

use Illuminate\Database\Seeder;

class NavigationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	    
    	$manageUserPermId = DB::table('permission')->where(['value'=>'permission_manage_user'])->value('id');
    	$adminControlPermId = DB::table('permission')->where(['value'=>'permission_admin_control'])->value('id');
    	
        // Navigation main menu items
        $today = new DateTime();
        $navigations = [
                ['name'=>'Dashboard','url'=>'/','class'=>'fa fa-desktop','created_at' => $today,'updated_at' => $today, 'order'=>1,'protected'=>1,'permission_id'=>0],              
                ['name'=>'Admin Control','url'=>'#','class'=>'fa fa-gears','created_at' => $today,'updated_at' => $today, 'order'=>5,'protected'=>0,'permission_id'=>$adminControlPermId],
        ];
    	DB::table('navigation')->insert($navigations);
    	
     	$adminConrolId = DB::table('navigation')->where(['name'=>'Admin Control'])->value('id');

    	if($adminConrolId)
    	{
    		// insert sub menus
    		$today = new DateTime();
    		$navigations = [
    				['name'=>'Manage Admin','url'=>'#','class'=>'fa fa-chevron-right','created_at' => $today,'updated_at' => $today, 'order'=>1, 'parent_id'=>$adminConrolId,'permission_id'=>$adminControlPermId],
    				['name'=>'Roles','url'=>'#','class'=>'fa fa-chevron-right','created_at' => $today,'updated_at' => $today, 'order'=>2, 'parent_id'=>$adminConrolId,'permission_id'=>$adminControlPermId],    				
    		];
    		DB::table('navigation')->insert($navigations);
    		
    	}
    }
}
