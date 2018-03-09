<?php

namespace App\Http\Controllers;


use App\Network;
use App\NetworkClick;
use App\Product;
use App\Report;
use Carbon\Carbon;
use function GuzzleHttp\Psr7\parse_query;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class FrontendController extends Controller
{
    public function index()
    {
        return view('welcome');
    }


    private function processClick($networkId, $networkClickUrl, $networkAuto, $request)
    {
        $goAwayUrl = null;

        try {
            \DB::beginTransaction();

            $networkClick = NetworkClick::create([
                'log_click_url' => $request->fullUrl(),
                'camp_ip' => $request->ip(),
                'camp_time' => Carbon::now()->toDateTimeString(),
                'network_id' => $networkId,
                'origin' => $request->server('HTTP_REFERER'),
                'time' => Carbon::now()->timestamp
            ]);

            $signUrl = (strpos($networkClickUrl, '?') === FALSE)? '?' : '&';
            $goAwayUrl = $networkClickUrl.$signUrl.'uid='.$networkClick->id;

            /*$networkClick->update([
                'redirect_to_end_point_url' => $goAwayUrl
            ]);*/

            if ($networkAuto) {

                if ($networkClick->id % 10000 == 0) {
                    //add one click.
                    Report::create([
                        'network_id' => $networkId,
                        'date' => Carbon::now()->toDateString(),
                        'phone' => uniqid(time()),
                    ]);
                }
            }

            \DB::commit();

        } catch (\Exception $e) {
            \DB::rollBack();
        }

        return $goAwayUrl;
    }

    public function camp(Request $request)
    {
        #dd('network Maintaine 1 mins!');
        $errorMsg = null;
        if ($request->input('network_id')) {
            $networkId = $request->input('network_id');

            if (Cache::has('network_id_'.$networkId)) {
                $networkData = Cache::get('network_id_'.$networkId);
            } else {
                $networkData = Network::find($networkId)->toArray();
                Cache::put('network_id_'.$networkId, $networkData, 10);
            }


            if ($networkData && $networkData['status']) {

                $goAwayUrl = $this->processClick($networkId, $networkData['click_url'], $networkData['auto'], $request);
                if ($goAwayUrl) {
                    return redirect()->away($goAwayUrl);
                }  else {
                    $errorMsg = 'Failed to insert!';
                }

            }  else {
                $errorMsg = 'Failed to process with networkId='.$request->input('network_id');
            }
        } else {
            $errorMsg = 'Network_id is required';
        }

        if ($errorMsg) {
            @file_put_contents(storage_path('logs/camp_errors.txt'), $errorMsg."\n", FILE_APPEND);
            return response()->json(['error' => $errorMsg]);
        }
    }

    // only for network->is_sms_callback = 0

    public function callback(Request $request)
    {
        $errorMsg = null;
        if ($request->input('uid')) {
            $networkClickId = (int) $request->input('uid');
            $networkClick = NetworkClick::find($networkClickId);
            if ($networkClick) {

                if (!$networkClick->log_callback_url) {
                    try {
                        $network = Network::find($networkClick->network_id);
                        $networkAllowIps = [];
                        $tempIps = explode(',', $network->callback_allow_ip);
                        foreach ($tempIps as $tempIp) {
                            $networkAllowIps[] = trim($tempIp);
                        }
                        if ($networkAllowIps && (in_array($request->ip(), $networkAllowIps))) {
                           if ($network->is_sms_callback == 0) {
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
                                       $callbackUrl .= (strpos($callbackUrl, '?') === FALSE)? '?' : '&';
                                       $callbackUrl .= $to_param.'='.$clickParams[$from_param];
                                   }
                               }
                               if ($network->extend_params) {
                                   $callbackUrl .= (strpos($callbackUrl, '?') === FALSE)? '?' : '&';
                                   $callbackUrl .= trim($network->extend_params);
                               }

                               $responseHtml = @file_get_contents($callbackUrl);

                               $ok = 'Match allow Ip! callback response url='.utf8_encode($responseHtml);


                               $sign = $request->input('sign') ? $request->input('sign') : null;

                               $networkClick->update([
                                   'log_callback_url' => $request->fullUrl(),
                                   'is_lead' => true,
                                   'sign' => $sign,
                                   'callback_ip' => $request->ip(),
                                   'callback_time' => Carbon::now()->toDateTimeString(),
                                   'call_start_point_url' => $callbackUrl,
                                   'call_start_point_status' => ($ok) ? true: false,
                                   'callback_response' => $ok
                               ]);
                           } else {
                               $errorMsg = 'Invalid Callback Mode';
                           }
                        } else {
                            $errorMsg = 'Not match allow Ip! Not call callback url!'. 'Allow ip list='.$network->callback_allow_ip. 'but ip access='.$request->ip();
                        }

                    } catch (\Exception $e) {
                        $errorMsg = $e->getMessage();
                    }
                } else {
                    $errorMsg = 'Already has callback for uid='.$request->input('uid');
                }

            } else {
                $errorMsg = 'Failed to process with uid='.$request->input('uid');
            }
        } else {
            $errorMsg = 'Uid is required';
        }

        if ($errorMsg) {
            @file_put_contents(storage_path('logs/callback_errors.txt'), $errorMsg."\n", FILE_APPEND);
            return response()->json(['error' => $errorMsg]);
        } else {
            return response()->json(['status' => true]);
        }
    }

    public function exampleCamp(Request $request)
    {
        $msg = json_encode($request->all(), true);
        @file_put_contents(storage_path('logs/example_camp.txt'), $msg."\n");
        $uid = $request->input('uid');

        # process with user here.

        # call the callback when user get conversion.

        $ok = @file_get_contents(url('callback?uid='.$uid.'&payout=0.3&country=VN&sign='.md5(time())));

        return response()->json(['msg' => $ok]);
    }

    public function exampleCallback(Request $request)
    {
        $msg = json_encode($request->all(), true);
        return response()->json(['msg' => $msg]);
    }

    public function source($uid)
    {

        try {
            $click = NetworkClick::findOrFail($uid);

            return response()->json([
                'uid' => $uid,
                'source' => $click->origin
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

    }

    //api for sending message base on SMS.
    // only for network->is_sms_callback = 1

    public function smsCallback(Request $request)
    {
        $errorMsg = null;
        if ($request->input('network_id')) {
            $networkId = (int) $request->input('network_id');

            $networkClickTrigger = NetworkClick::where('network_id', $networkId)
                ->where('is_lead', false)
                ->limit(1)
                ->get();

            $networkClick = null;

            if ($networkClickTrigger->count() > 0) {
                $networkClick = $networkClickTrigger->first();
            }

            if ($networkClick) {
                try {
                    $network = Network::find($networkClick->network_id);
                    $networkAllowIps = [];
                    if ($network->callback_allow_ip) {
                        $tempIps = explode(',', $network->callback_allow_ip);
                        foreach ($tempIps as $tempIp) {
                            $networkAllowIps[] = trim($tempIp);
                        }
                    }


                    if (($networkAllowIps && (in_array($request->ip(), $networkAllowIps))) || !$networkAllowIps) {
                       if ($network->is_sms_callback == 1) {
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
                                   $callbackUrl .= (strpos($callbackUrl, '?') === FALSE)? '?' : '&';
                                   $callbackUrl .= $to_param.'='.$clickParams[$from_param];
                               }
                           }
                           if ($network->extend_params) {
                               $callbackUrl .= (strpos($callbackUrl, '?') === FALSE)? '?' : '&';
                               $callbackUrl .= trim($network->extend_params);
                           }

                           $responseHtml = @file_get_contents($callbackUrl);

                           $ok = 'Match allow Ip! callback response url='.utf8_encode($responseHtml);

                           $sign = $request->input('sign') ? $request->input('sign') : null;

                           $networkClick->update([
                               'log_callback_url' => $request->fullUrl(),
                               'is_lead' => true,
                               'sign' => $sign,
                               'callback_ip' => $request->ip(),
                               'callback_time' => Carbon::now()->toDateTimeString(),
                               'call_start_point_url' => $callbackUrl,
                               'call_start_point_status' => ($ok) ? true: false,
                               'callback_response' => $ok
                           ]);
                       } else {
                           $errorMsg = "Invalid Callback Mode!";
                       }
                    } else {
                        $errorMsg = 'Not match allow Ip! Not call callback url!'. 'Allow ip list='.$network->callback_allow_ip. 'but ip access='.$request->ip();
                    }

                } catch (\Exception $e) {
                    $errorMsg = $e->getMessage();
                }

            } else {
                $errorMsg = "Can not find any unsuccessful click with this network_id!";
            }
        } else {
            $errorMsg = 'NetworkId is required';
        }

        if ($errorMsg) {
            @file_put_contents(storage_path('logs/sms_callback_errors.txt'), $errorMsg."\n", FILE_APPEND);
            return response()->json(['error' => $errorMsg]);
        } else {
            return response()->json(['status' => true]);
        }
    }

    public function report(Request $request)
    {
        $networkId = $request->get('network_id');
        $startDate = $request->get('start');
        $endDate = $request->get('end');

        $response = [];

        if ($networkId && $startDate && $endDate) {
            $network = Network::where('id', $networkId)->where('is_sms_callback', 2)->get();

            if ($network->count() > 0) {
                $network = $network->first();


                $reports = Report::where('network_id', $network->id)->whereBetween('date', [$startDate, $endDate])->get();

                foreach ($reports as $report) {
                    $response[] = [
                        'msisdn' => $report->phone,
                        'createDate' => $report->created_at->toDateString()
                    ];
                }
            }
        }
        return response()->json($response);
    }

}
