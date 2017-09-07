<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldTimesToNetworkClicks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('network_clicks', function (Blueprint $table) {
            $table->string('camp_time')->nullable();
            $table->string('callback_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('network_clicks', function (Blueprint $table) {
            $table->dropColumn(['camp_time', 'callback_time']);
        });
    }
}
