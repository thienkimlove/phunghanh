<?php

namespace App\Http\Controllers;


use App\Category;
use App\Network;
use App\NetworkClick;
use App\Product;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function camp(Request $request)
    {
        $errorMsg = null;
        if ($request->input('network_id')) {
            $networkId = $request->input('network_id');
            if (($network = Network::find($networkId)) && $network->status) {
                try {
                    $networkClick = NetworkClick::create([
                        'log_click_url' => json_encode($request->all(), true),
                        'network_id' => $networkId
                    ]);

                    $signUrl = (strpos($network->click_url, '?') === FALSE)? '?' : '&';
                    $goAwayUrl = $network->click_url.$signUrl.'uid='.$networkClick->id;
                    @file_put_contents(storage_path('logs/go_away_url.txt'), $goAwayUrl."\n", FILE_APPEND);

                    $networkClick->update([
                        'redirect_to_end_point_url' => $goAwayUrl
                    ]);

                    return redirect()->away($goAwayUrl);
                } catch (\Exception $e) {
                    $errorMsg = $e->getMessage();
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
                        # retrieve click params.
                        $clickParams = json_decode($networkClick->log_click_url, true);
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

                        $ok = @file_get_contents($callbackUrl);

                        $sign = $request->input('sign') ? $request->input('sign') : null;

                        $networkClick->update([
                            'log_callback_url' => json_encode([
                                'url' => $callbackUrl,
                                'call_status' => $ok
                            ]),
                            'sign' => $sign,
                            'callback_ip' => $request->ip(),
                            'call_start_point_url' => $callbackUrl,
                            'call_start_point_status' => ($ok) ? true: false
                        ]);


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

        # call the callback

        return redirect()->away(url('callback?uid='.$uid.'&payout=0.3&country=VN'));
    }

    public function exampleCallback(Request $request)
    {
        $msg = json_encode($request->all(), true);
        return response()->json(['msg' => $msg]);
    }

}
