<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserToRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_to_role', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->unsignedInteger('user_id')->index()->default(0);
			$table->unsignedInteger('role_id')->index()->default(0);
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_to_role');
    }
}
