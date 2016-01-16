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
        DB::table('users')->insert([
    			'created_at' => new DateTime(),
        		'updated_at' => new DateTime(),
    			'firstname' => 'Gorabel',
                'lastname' => 'Admin',
    			'email' => 'admin@email.com',
    			'password' => bcrypt('admin')
    	]);
    	 
    }
}
