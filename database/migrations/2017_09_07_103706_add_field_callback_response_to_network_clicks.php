<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldCallbackResponseToNetworkClicks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('network_clicks', function (Blueprint $table) {
            $table->text('callback_response')->nullable();
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
            $table->dropColumn('callback_response');
        });
    }
}
