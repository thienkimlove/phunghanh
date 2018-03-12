<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTotalClicks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('total_clicks', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('network_id')->index();
            $table->string('date')->index();
            $table->unsignedBigInteger('total')->default(0);
            $table->unique(['network_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('total_clicks');
    }
}
