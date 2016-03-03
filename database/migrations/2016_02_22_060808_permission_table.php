<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('permission', function(Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name', 255);
            $table->string('name_translation', 255)->nullable();     
            $table->string('value', 255);
            $table->string('category', 255);
            $table->unsignedInteger('active')->default(1);            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('permission');
    }
}
