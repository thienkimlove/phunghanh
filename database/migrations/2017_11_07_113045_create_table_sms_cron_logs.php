<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSmsCronLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_cron_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('network_id');
            $table->text('call_url');
            $table->longText('response_content');
            $table->timestamps();
        });

        Schema::create('sms_cron_contents', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('network_id');
            $table->unsignedInteger('sms_cron_log_id');
            $table->string('msisdn');
            $table->string('date');
            $table->boolean('send_to_partner')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sms_cron_logs');
        Schema::dropIfExists('sms_cron_contents');
    }
}
