<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RoleToNavTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_to_nav', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->unsignedInteger('role_id')->index()->default(0);
			$table->unsignedInteger('navigation_id')->index()->default(0);			
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('role_to_nav');
    }
}
