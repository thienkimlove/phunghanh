<?php

namespace App\Http\Controllers;

use App\Network;
use App\NetworkClick;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class NetworkClicksController extends AdminController
{

    public $model = 'network_clicks';

    public $validator = [

    ];
    private function init()
    {
        return '\\App\\' . ucfirst(camel_case(str_singular($this->model)));
    }
    public function index(Request $request)
    {
        $modelClass = $this->init();
        $networkId = null;

        $start = $request->has('start')? $request->get('start') : Carbon::now()->startOfMonth()->toDateString();
        $end = $request->has('end') ? $request->get('end') : Carbon::now()->endOfMonth()->toDateString();

        if ($end < $start) {
            $end = $start;
        }

        $customUrl = $this->model.'?start='.$start.'&end='.$end;

        $networkConversions = NetworkClick::join('networks', 'network_clicks.network_id', '=', 'networks.id')
            ->whereBetween('network_clicks.created_at', [$start, $end]);

        $conversion = null;

        $networks = Network::pluck('name', 'id')->all();

        $contents = $modelClass::latest('created_at')
            ->whereBetween('network_clicks.created_at', [$start, $end]);

        if ($request->has('network_id')) {
            $networkId = $request->get('network_id');
            $contents = $contents->where('network_id', $networkId);
            $customUrl .= '&network_id='.$networkId;
            $networkConversions = $networkConversions->where('network_clicks.network_id', $networkId);
        }

        if ($request->has('conversion')) {
            $conversion  = $request->get('conversion');
            $contents = ($conversion == 1) ? $contents->whereNotNull('log_callback_url') : $contents->whereNull('log_callback_url');
            $customUrl .= '&conversion='.$conversion;
            $networkConversions = ($conversion == 1)?  $networkConversions->whereNotNull('network_clicks.log_callback_url') : $networkConversions->whereNull('log_callback_url');
        }
        $networkConversions = $networkConversions->selectRaw('COUNT(network_clicks.id) as total, networks.name as network_name')
            ->groupBy('network_clicks.network_id')
            ->get();

        $showExport = false;

        if ($conversion && $networkId) {

            $showExport = true;

            if ($request->has('export')) {
                Excel::create('network_id_'.$networkId, function($excel) use ($contents) {

                    $excel->setTitle('Conversion');

                    $contents = $contents->get();

                    $excel->sheet('Big', function($sheet) use ($contents) {

                        $i = 1;

                        $sheet->row($i , array(
                            'Time', 'Uid', 'Callback Sign', 'IP'
                        ));

                        foreach ($contents as $content) {
                            $i ++;
                            $sheet->row($i , array(
                                $content->callback_time, $content->id , '"'.$content->sign.'"' , $content->callback_ip
                            ));
                        }

                    });

                })->download('xls');
            }
        }




        $contents = $contents->paginate(20);

        $contents->setPath($customUrl);

        return view('admin.'.$this->model.'.index', compact(
            'contents',
            'start',
            'end',
            'networkId',
            'conversion',
            'networkConversions',
            'networks',
            'showExport'
        ))->with('model', $this->model);
    }


}
