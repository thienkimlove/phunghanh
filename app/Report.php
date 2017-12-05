<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'network_id',
        'date',
        'phone'
    ];

    public function network()
    {
        return $this->belongsTo(Network::class);
    }
}
