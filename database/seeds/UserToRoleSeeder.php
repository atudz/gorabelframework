<?php

use Illuminate\Database\Seeder;

class UserToRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $adminID = DB::table('role')->where(['name'=>'Administrator'])->value('id');
    	$customerID = DB::table('role')->where(['name'=>'Customer'])->value('id');
    	
    	if($adminID)
    	{    		
    		$today = new DateTime();
            $mappings[] = ['user_id'=>1,'role_id'=>$adminID,'created_at' => $today,'updated_at' => $today];         
    		$mappings[] = ['user_id'=>2,'role_id'=>$customerID,'created_at' => $today,'updated_at' => $today];   		
    		DB::table('user_to_role')->insert($mappings);
    	}
    }
}
