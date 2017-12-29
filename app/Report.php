<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use DataTables;

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

    public static function getDataTables($request)
    {
        $report = static::select('*')->latest('created_at');

        return DataTables::of($report)
            ->filter(function ($query) use ($request) {

                if ($request->has('network_id')) {
                    $query->where('network_id', $request->get('network_id'));
                }

                if ($request->has('date')) {
                    $query->where('date', $request->get('date'));
                }

            })->addColumn('network_name', function ($report) {
                return $report->network->name;
            })->addColumn('send_to_partner', function ($report) {
                return (SmsCronContent::where('network_id', $report->network_id)->where('msisdn', $report->phone)->where('send_to_partner', true)->count() > 0) ? 'Đã Gửi' : 'Chưa gửi';
            })
            ->rawColumns(['network_name', 'send_to_partner'])
            ->make(true);
    }
}
