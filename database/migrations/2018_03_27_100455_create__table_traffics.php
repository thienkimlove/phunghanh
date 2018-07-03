<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTraffics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('links', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('network_id')->index();
            $table->string('link');
            $table->timestamps();

        });


        Schema::create('traffics', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('network_id')->index();
            $table->unsignedInteger('link_id')->index();
            $table->unsignedInteger('minute')->index();
            $table->unsignedInteger('click');

            $table->unique(['network_id', 'link_id', 'minute']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('links');
        Schema::dropIfExists('traffics');
    }
}
