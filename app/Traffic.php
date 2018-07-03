<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use DataTables;

class Traffic extends Model
{
    protected $fillable = ['network_id', 'link_id', 'minute', 'click'];

    protected $table = 'traffics';

    public $timestamps = false;

    public function link()
    {
        return $this->belongsTo(Link::class);
    }

    public function network()
    {
        return $this->belongsTo(Network::class);
    }

    public static function getDataTables($request)
    {
        $traffic = static::select('*')->orderBy('minute', 'desc');

        return DataTables::of($traffic)
            ->filter(function ($query) use ($request) {

                if ($request->has('network_id')) {
                    $query->where('network_id', $request->get('network_id'));
                }

                if ($request->has('link')) {
                    $query->whereHas('link', function ($q) use($request) {
                        $q->where('link', $request->get('link'));
                    });
                }

            })->addColumn('network_name', function ($traffic) {
                return $traffic->network->name;
            })->addColumn('link_name', function ($traffic) {
                return $traffic->link->link;
            })->addColumn('minute_format', function ($traffic) {
                return Carbon::createFromFormat('YmdHi', $traffic->minute)->format('Y-m-d H:i');
            })
            ->rawColumns(['network_name', 'link_name', 'minute_format'])
            ->make(true);
    }
}
