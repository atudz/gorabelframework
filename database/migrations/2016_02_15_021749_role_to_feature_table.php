<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RoleToFeatureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feature_to_role', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->unsignedInteger('role_id')->index()->default(0);
			$table->unsignedInteger('feature_id')->index()->default(0);
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('feature_to_role');
    }
}
