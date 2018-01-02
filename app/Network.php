<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;
use DataTables;

class Network extends Model
{

    use Sluggable;
    use SluggableScopeHelpers;

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    protected $fillable = [
        'name',
        'slug',
        'click_url',
        'callback_url',
        'map_params',
        'extend_params',
        'status',
        'callback_allow_ip',
        'is_sms_callback',
        'cron_url',
        'auto'
    ];

    public static function getDataTables($request)
    {
        $network = static::select('*');

        return DataTables::of($network)
            ->filter(function ($query) use ($request) {
                if ($request->has('name')) {
                    $query->where('name', 'like', '%' . $request->get('name') . '%');
                }

                if ($request->has('is_sms_callback')) {
                    $query->where('is_sms_callback', $request->get('is_sms_callback'));
                }

                if ($request->has('status')) {
                    $query->where('status', $request->get('status'));
                }

                if ($request->has('auto')) {
                    $query->where('auto', $request->get('auto'));
                }

            })

            ->editColumn('status', function ($network) {
                return $network->status ? '<i class="ion ion-checkmark-circled text-success"></i>' : '<i class="ion ion-close-circled text-danger"></i>';
            })->editColumn('click_url', function ($network) {
                return $network->click_url;
            })
            ->editColumn('auto', function ($network) {
                return $network->auto ? '<i class="ion ion-checkmark-circled text-success"></i>' : '<i class="ion ion-close-circled text-danger"></i>';
            })->addColumn('callback', function ($network) {
                $response = '<span><b>Callback URL</b> '.$network->callback_url.'</span><br/><span><b>Map Params</b> '.$network->map_params.'</span><br/>';

                if ($network->extend_params) {
                    $response .= '<span><b>Extend Params</b> '.$network->extend_params.'</span><br/>';
                }

                if ($network->callback_allow_ip) {
                    $response .= '<span><b>Allow IPs</b> '.$network->callback_allow_ip.'</span><br/>';
                }

                return $response;
            })

            ->addColumn('action', function ($network) {
                return '<a class="table-action-btn" title="Chỉnh sửa Network" href="' . route('networks.edit', $network->id) . '"><i class="fa fa-pencil text-success"></i></a> <a class="table-action-btn" id="btn-connect-' . $network->id . '" title="Show Connection" data-url="' . route('networks.connect', $network->id) . '" href="javascript:;"><i class="fa fa-terminal text-warning"></i></a>';

            })
            ->rawColumns(['action', 'name', 'status', 'callback', 'auto'])
            ->make(true);
    }
}
