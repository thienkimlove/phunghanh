<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToNetworkClicks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('network_clicks', function (Blueprint $table) {
            $table->text('redirect_to_end_point_url')->nullable();
            $table->text('call_start_point_url')->nullable();
            $table->boolean('call_start_point_status')->default(true);
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
            $table->dropColumn([
                'redirect_to_end_point_url',
                'call_start_point_url',
                'call_start_point_status',
            ]);
        });
    }
}
