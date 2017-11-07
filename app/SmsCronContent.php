<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmsCronContent extends Model
{
    protected $fillable = [
        'network_id',
        'sms_cron_log_id',
        'msisdn',
        'date',
        'send_to_partner',
    ];
}
