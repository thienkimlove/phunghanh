<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use DataTables;

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

    public static function getDataTables($request)
    {
        $network_click = static::select('*')->with('network');

        return DataTables::of($network_click)
            ->filter(function ($query) use ($request) {


                if ($request->has('network_id')) {
                    $query->where('network_id', $request->get('network_id'));
                }
                if ($request->has('date')) {
                    $dateRange = explode(' - ', $request->get('date'));
                    $query->whereDate('created_at', '>=', Carbon::createFromFormat('d/m/Y', $dateRange[0])->toDateString());
                    $query->whereDate('created_at', '<=', Carbon::createFromFormat('d/m/Y', $dateRange[1])->toDateString());
                }

                if ($request->has('conversion')) {
                    $conversion  = $request->get('conversion');
                    if ($conversion == 1) {
                        $query->whereNotNull('log_callback_url');
                    } else {
                        $query->whereNull('log_callback_url');
                    }
                }

            })->addColumn('network_name', function ($network_click) {
                return $network_click->network->name;
            })->addColumn('start_to_camp', function ($network_click) {
                $response =  '<span>Link <b>'.Site::displayJson($network_click->log_click_url).'</b></span><br/>';
                $response .=  '<span>Time <b>'.$network_click->camp_time.'</b></span><br/>';
                $response .=  '<span>IP <b>'.$network_click->camp_ip.'</b></span><br/>';

                return $response;
            })->addColumn('camp_to_end', function ($network_click) {
                return $network_click->redirect_to_end_point_url;
            })->addColumn('end_to_camp', function ($network_click) {
                $response =  '<span>Link <b>'.Site::displayJson($network_click->log_callback_url).'</b></span><br/>';
                $response .=  '<span>Time <b>'.$network_click->callback_time.'</b></span><br/>';
                $response .=  '<span>IP <b>'.$network_click->callback_ip.'</b></span><br/>';

                return $response;
            })->addColumn('camp_to_start', function ($network_click) {
                return Site::displayJson($network_click->call_start_point_url);
            })->addColumn('start_response', function ($network_click) {
                return $network_click->callback_response;
            })

            ->rawColumns(['network_name', 'start_to_camp', 'camp_to_end', 'end_to_camp', 'camp_to_start', 'start_response'])
            ->make(true);
    }


    public static function exportToExcel($request)
    {
        ini_set('memory_limit', '2048M');

        $query = NetworkClick::join('networks', 'network_clicks.network_id', '=', 'networks.id');

        if ($request->has('filter_network_id')) {
            $query->where('network_clicks.network_id', $request->get('filter_network_id'));
        }

        if ($request->has('filter_conversion')) {
            $conversion  = $request->get('filter_conversion');
            if ($conversion == 1) {
                $query->whereNotNull('network_clicks.log_callback_url');
            } else {
                $query->whereNull('network_clicks.log_callback_url');
            }
        }

        if ($request->filled('filter_date')) {
            $dateRange = explode(' - ', $request->get('filter_date'));
            $query->whereDate('network_clicks.created_at', '>=', Carbon::createFromFormat('d/m/Y', $dateRange[0])->toDateString());
            $query->whereDate('network_clicks.created_at', '<=', Carbon::createFromFormat('d/m/Y', $dateRange[1])->toDateString());
        }


        $reports = $query->selectRaw("networks.name as network_name, network_clicks.camp_ip as network_click_ip")->get();

        return (new static())->createExcellFile($reports);
    }

    public function createExcellFile($reports)
    {
        $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
        $objPHPExcel = $objReader->load(resource_path('templates/results.xlsx'));

        $row = 2;
        foreach ($reports as $report) {
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $row - 1)
                ->setCellValue('B'.$row, $report->network_name)
                ->setCellValue('C'.$row, $report->network_click_ip);

            $row++;
        }


        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

        $path = 'reports_'.date('Y_m_d_His').'.xlsx';

        $objWriter->save(storage_path('app/public/' . $path));

        return redirect('/storage/' . $path);
    }

}
