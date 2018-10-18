<?php

namespace App\Console\Commands;

use App\Network;
use App\NetworkClick;
use App\SmsCronContent;
use App\SmsCronLog;
use Carbon\Carbon;
use DB;
use function GuzzleHttp\Psr7\parse_query;
use Illuminate\Console\Command;

class SmsCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run cron job every 10 minutes to update conversion';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    private function runningCron($network)
    {
        if ($network->cron_url) {
            DB::beginTransaction();

            try {

                $startTime = Carbon::now()->toDateString();
                $endTime = Carbon::now()->toDateString();

                $url = str_replace('#START', $startTime, $network->cron_url);
                $url = str_replace('#END', $endTime, $url);

                $smsCronLog = SmsCronLog::create([
                    'network_id' => $network->id,
                    'call_url' => $url,
                    'response_content' => ''
                ]);

                $response = @file_get_contents($url);

                $smsCronLog->update(['response_content' => $response]);

                $contents = json_decode($response, true);

                if ($contents) {
                    foreach ($contents as $content) {
                        if (isset($content['msisdn']) && isset($content['createDate'])) {
                            $phone = trim($content['msisdn']);
                            $date = Carbon::parse(trim($content['createDate']))->toDateTimeString();
                            $countExisted = SmsCronContent::where('network_id', $network->id)->where('msisdn', $phone)->count();
                            if ($countExisted == 0) {
                                SmsCronContent::create([
                                    'network_id' => $network->id,
                                    'sms_cron_log_id' => $smsCronLog->id,
                                    'msisdn' => $phone,
                                    'date' => $date
                                ]);
                            }

                        }
                    }

                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                $this->line($e->getMessage());
            }
        }
    }

    private function fireCallback($content)
    {
        try {
            $networkClickTrigger = NetworkClick::where('network_id', $content->network_id)
                ->where('is_lead', false)
                ->limit(1)
                ->get();

            $networkClick = null;

            if ($networkClickTrigger->count() > 0) {
                $networkClick = $networkClickTrigger->first();
            } else {
                $networkClick = NetworkClick::create([
                    'log_click_url' => 'http://media.seniorphp.net',
                    'camp_ip' => '42.112.31.173',
                    'camp_time' => Carbon::now()->subHour(1)->toDateTimeString(),
                    'network_id' => $content->network_id,
                    'origin' => '',
                    'time' => Carbon::now()->subHour(1)->timestamp
                ]);
            }

            if ($networkClick) {
                $network = Network::find($networkClick->network_id);
                if ($network->is_sms_callback == 2) {
                    # retrieve click params.
                    $clickParams = parse_query($networkClick->log_click_url);

                    $query_str = parse_url($networkClick->log_click_url, PHP_URL_QUERY);
                    parse_str($query_str, $clickParams);
                    $mapParams = explode(',', $network->map_params);
                    # request

                    # build callback Url
                    $callbackUrl = $network->callback_url;

                    foreach ($mapParams as $couple) {
                        $tempCouple = explode(':', trim($couple));
                        $from_param = trim($tempCouple[0]);
                        $to_param = trim($tempCouple[1]);
                        if (isset($clickParams[$from_param]) && $clickParams[$from_param]) {

                            if (strpos($callbackUrl, '{'.$to_param.'}') !== FALSE) {
                                $callbackUrl = str_replace('{'.$to_param.'}', $clickParams[$from_param], $callbackUrl);
                            } else {
                                $callbackUrl .= (strpos($callbackUrl, '?') === FALSE)? '?' : '&';
                                $callbackUrl .= $to_param.'='.$clickParams[$from_param];
                            }

                        }
                    }
                    if ($network->extend_params) {
                        $callbackUrl .= (strpos($callbackUrl, '?') === FALSE)? '?' : '&';
                        $callbackUrl .= trim($network->extend_params);
                    }

                    if ($content->msisdn) {
                       // $callbackUrl .= (strpos($callbackUrl, '?') === FALSE)? '?' : '&';
                        $callbackUrl .= '&msisdn='.$content->msisdn;
                    }

                    $responseHtml = @file_get_contents($callbackUrl);

                    $ok = 'Match allow Ip! callback response url='.utf8_encode($responseHtml);

                    $networkClick->update([
                        'log_callback_url' => $network->cron_url,
                        'is_lead' => true,
                        'sign' => $content->id,
                        'callback_ip' => '42.112.31.173',
                        'callback_time' => Carbon::now()->toDateTimeString(),
                        'call_start_point_url' => $callbackUrl,
                        'call_start_point_status' => ($ok) ? true: false,
                        'callback_response' => $ok
                    ]);

                    $content->update(['send_to_partner' => true]);

                } else {
                    $this->line("Invalid Callback Mode!");
                }

            } else {
                $this->line("Can not find any unsuccessful click with this network_id=".$content->network_id);
            }

        } catch (\Exception $e) {

            $this->line($e->getMessage());
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $needCronNetworks = Network::where('status', true)->where('is_sms_callback', 2)->get();

        if ($needCronNetworks->count() > 0) {
            foreach ($needCronNetworks as $network) {
                $this->runningCron($network);
            }
        }

        $needFireCallbackContents = SmsCronContent::where('send_to_partner', false)->get();

        if ($needFireCallbackContents->count() > 0) {
            foreach($needFireCallbackContents as $content) {
                $this->fireCallback($content);
            }
        }
    }
}
