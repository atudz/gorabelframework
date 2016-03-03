<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NavigationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('navigation', function(Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name', 255);
            $table->string('name_translation', 255)->nullable();
            $table->string('url', 255)->nullable();
            $table->string('class', 255)->nullable();
            $table->string('value')->index();
            $table->unsignedInteger('parent_id')->index()->default(0);
            $table->unsignedInteger('permission_id')->index()->default(0);
            $table->unsignedInteger('order')->default(0);  
            $table->unsignedInteger('active')->default(1);
            $table->tinyInteger('protected')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('navigation');
    }
}
