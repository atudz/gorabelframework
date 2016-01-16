<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('firstname', 255);
			$table->string('lastname', 255);
			$table->string('middlename', 255)->nullable();
			$table->string('password', 255);
			$table->string('email', 255)->unique()->nullable();
			$table->rememberToken();
			$table->softDeletes();			
		}); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
