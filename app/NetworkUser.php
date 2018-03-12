<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NetworkUser extends Model
{
    protected $fillable = [
        'network_id',
        'user_id'
    ];

    public function network()
    {
        return $this->belongsTo(Network::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
