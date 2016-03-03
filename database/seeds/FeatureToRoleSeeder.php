<?php

use Illuminate\Database\Seeder;

class FeatureToRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            
    		$today = new DateTime();
    	
            $adminFeatures = [
            		'feature_cms',
            		'feature_add_user',
            		'feature_edit_user',
            		'feature_delete_user',            		
            		'feature_add_role',
            		'feature_edit_role',
            		'feature_delete_role',
            		'feature_edit_permission',
            ];
                        
            $data = [];
            $adminRoleId = DB::table('role')->where(['value'=>'role_admin'])->value('id');
            $features = DB::table('feature')->whereIn('value',$adminFeatures)->get(['id']);
            $featureIds = [];
            foreach($features as $row)
            {
            	$featureIds[] = $row->id;
            }

            foreach($featureIds as $feature)
            {
            	$data[] = [
		        		'role_id'=>$adminRoleId,
		        		'created_at' => $today,
		        		'feature_id'=>$feature,
		        		'updated_at' => $today             		
            	];
            }
            
            // Add admin the admin feature
	        DB::table('feature_to_role')->insert($data);
	       
    }
}
