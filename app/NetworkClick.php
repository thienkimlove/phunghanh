<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NetworkClick extends Model
{
    protected $fillable = [
        'network_id',
        'log_click_url',
        'log_callback_url',
        'sign',
        'callback_ip',
        'redirect_to_end_point_url',
        'call_start_point_url',
        'call_start_point_status',
        'camp_ip',
        'camp_time',
        'callback_time',
        'callback_response',
        'origin',
        'time'
    ];

    public function network()
    {
        return $this->belongsTo(Network::class);
    }
}
