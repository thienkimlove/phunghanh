<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TotalClick extends Model
{
    protected $fillable = ['network_id', 'date', 'total'];

    public $timestamps = false;
}
