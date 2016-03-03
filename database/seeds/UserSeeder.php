<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	
    	$today = new DateTime();
        DB::table('user')->insert([[
    			'created_at' => $today,
        		'updated_at' => $today,
    			'fullname' => 'Gorabel Admin',
                'email' => 'admin@email.com',
    			'password' => bcrypt('test1234')
    	], [
                'created_at' => $today,
                'updated_at' => $today,
                'fullname' => 'Gorable Customer',
                'email' => 'customer@email.com',
                'password' => bcrypt('test1234')
        ]]);
    	 
    }
}
