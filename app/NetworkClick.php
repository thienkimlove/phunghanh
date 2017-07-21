<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NetworkClick extends Model
{
    protected $fillable = [
        'network_id',
        'log_click_url',
        'log_callback_url',
    ];

    public function network()
    {
        return $this->belongsTo(Network::class);
    }
}
