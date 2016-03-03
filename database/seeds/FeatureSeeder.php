<?php

use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $today = new DateTime();        
        $manageUserPermId = DB::table('permission')->where(['value'=>'permission_manage_user'])->value('id');
        $adminControlPermId = DB::table('permission')->where(['value'=>'permission_admin_control'])->value('id');
        
        DB::table('feature')->insert([
        		['name'=>'Add User','created_at' => $today,'value'=>'feature_add_user','updated_at' => $today, 'system'=>0,'permission_id'=>$manageUserPermId],
        		['name'=>'Edit User','created_at' => $today,'value'=>'feature_edit_user','updated_at' => $today, 'system'=>0,'permission_id'=>$manageUserPermId],
        		['name'=>'Delete User','created_at' => $today,'value'=>'feature_delete_user','updated_at' => $today, 'system'=>0,'permission_id'=>$manageUserPermId],        		        		
        		['name'=>'Add Roles','created_at' => $today,'value'=>'feature_add_role','updated_at' => $today, 'system'=>0,'permission_id'=>$adminControlPermId],
        		['name'=>'Edit Roles','created_at' => $today,'value'=>'feature_edit_role','updated_at' => $today, 'system'=>0,'permission_id'=>$adminControlPermId],
        		['name'=>'Delete Roles','created_at' => $today,'value'=>'feature_delete_role','updated_at' => $today, 'system'=>0,'permission_id'=>$adminControlPermId],
        		['name'=>'Edit Permissions','created_at' => $today,'value'=>'feature_edit_permission','updated_at' => $today, 'system'=>0,'permission_id'=>$adminControlPermId],
    	]);      
        
    }
}
