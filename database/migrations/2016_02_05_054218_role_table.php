<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->string('name', 255);
			$table->string('name_translation', 255)->nullable();	
			$table->string('value')->nullable()->index();
			$table->string('remarks', 255)->nullable();
			$table->tinyInteger('system')->default(0);
			$table->unsignedTinyInteger('active')->default(1);
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('role');
    }
}
