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
        'time',
        'is_lead'
    ];

    public function network()
    {
        return $this->belongsTo(Network::class);
    }

    public static function getDataTables($request)
    {
        $network_click = static::select('*')->with('network')->latest('time');

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
                        $query->where('is_lead', true);
                    } else {
                        $query->where('is_lead', false);
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

        $query = NetworkClick::join('networks', 'network_clicks.network_id', '=', 'networks.id')
            ->where('network_clicks.is_lead', true);


        $dateRange = explode(' - ', $request->get('filter_date'));
        $startDate = Carbon::createFromFormat('d/m/Y', $dateRange[0])->toDateString();
        $endDate = Carbon::createFromFormat('d/m/Y', $dateRange[1])->toDateString();
        $query = $query->whereDate('network_clicks.created_at', '>=', $startDate);
        $query = $query->whereDate('network_clicks.created_at', '<=', $endDate);


        $reports = $query->selectRaw("COUNT(network_clicks.id) as total, networks.id as network_id, networks.name as network_name, networks.click_url as click_url,  networks.callback_url as network_callback_url")
            ->groupBy('networks.id')
            ->get();

        return (new static())->createExcellFile($reports, $startDate, $endDate);
    }

    private function get_domain($url)
    {
        $res = null;

        $url_data = json_decode($url, true);
        foreach ($url_data as $data) {
            if ($data['click_url']) {
                $sourceUrl = parse_url($data['click_url']);
                $res .= $sourceUrl['host'].',';
            }

        }

        return $res;

    }

    public function createExcellFile($reports,  $startDate, $endDate)
    {
        $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
        $objPHPExcel = $objReader->load(resource_path('templates/results.xlsx'));

        $row = 2;
        foreach ($reports as $report) {



            $totalClicks = TotalClick::where('network_id', $report->network_id)->get();

            if ($totalClicks->count() > 0) {
                $totalClicks = $totalClicks->first()->total;
            } else {
                $totalClicks = 0;
            }

            $connection = Connection::where('callback', $report->network_callback_url)->get();

            if ($connection->count() > 0) {
                $connectionName = $connection->first()->name;
            } else {
                $connectionName = 'N/A';
            }

            $objPHPExcel->getActiveSheet()
                ->setCellValue('A'.$row, $report->network_id)
                ->setCellValue('B'.$row, $report->network_name)
                ->setCellValue('C'.$row, $this->get_domain($report->click_url))
                ->setCellValue('D'.$row, $connectionName)
                ->setCellValue('E'.$row, $totalClicks)
                ->setCellValue('F'.$row, $report->total)
                ->setCellValue('G'.$row, $startDate)
                ->setCellValue('H'.$row, $endDate);

            $row++;
        }


        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

        $path = 'reports_'.date('Y_m_d_His').'.xlsx';

        $objWriter->save(storage_path('app/public/' . $path));

        return redirect('/storage/' . $path);
    }

}
