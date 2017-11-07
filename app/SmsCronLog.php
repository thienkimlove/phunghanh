<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmsCronLog extends Model
{
    protected $fillable = [
        'network_id',
        'call_url',
        'response_content'
    ];
}
