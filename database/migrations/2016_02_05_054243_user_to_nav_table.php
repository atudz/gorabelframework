<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserToNavTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_to_nav', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->unsignedInteger('navigation_id')->index()->default(0);
			$table->unsignedInteger('user_id')->index()->default(0);	
			$table->unsignedInteger('enable')->index()->default(1);
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_to_nav');
    }
}
