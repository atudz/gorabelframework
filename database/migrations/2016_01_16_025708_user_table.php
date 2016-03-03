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
        Schema::create('user', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('fullname', 255);
			$table->string('password', 255);
			$table->string('username', 255)->nullable();
			$table->string('email', 255)->unique();			
			$table->string('hash_key', 255)->nullable();
			$table->dateTime('date_activated')->nullable();		
			$table->unsignedTinyInteger('activated')->default(1);
			$table->unsignedInteger('creator_id')->index()->default(0);
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
        Schema::drop('user');
    }
}
