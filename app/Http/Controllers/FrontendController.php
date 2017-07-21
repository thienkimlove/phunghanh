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

                    return redirect()->away($network->click_url.$signUrl.'uid='.$networkClick->id);
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
            $networkClickId = $request->input('uid');
            if ($networkClick = NetworkClick::find($networkClickId)) {
                try {

                    $network = Network::find($networkClick->network_id);
                    # retrieve click params.
                    $clickParams = json_decode($networkClick->log_click_url, true);
                    $mapParams = explode(',', $network->map_params);
                    # request

                    # build callback Url
                    $callbackUrl = $network->callback_url;

                    foreach ($mapParams as $couple) {
                       $tempCouple = explode(':', $couple);
                       $from_param = $tempCouple[0];
                       $to_param = $tempCouple[1];
                       $callbackUrl .= (strpos($callbackUrl, '?') === FALSE)? '?' : '&';
                       if (isset($clickParams[$from_param]) && $clickParams[$from_param]) {
                           $callbackUrl .= $to_param.'='.$clickParams[$from_param];
                       }
                    }
                    if ($network->extend_params) {
                        $callbackUrl .= (strpos($callbackUrl, '?') === FALSE)? '?' : '&';
                        $callbackUrl .= $network->extend_params;
                    }
                    $networkClick->update(['log_callback_url' => $callbackUrl]);

                    return redirect()->away($callbackUrl);

                } catch (\Exception $e) {
                    $errorMsg = $e->getMessage();
                }
            } else {
                $errorMsg = 'Failed to process with uid='.$request->input('uid');
            }
        }  else {
            $errorMsg = 'Uid is required';
        }

        if ($errorMsg) {
            @file_put_contents(storage_path('logs/callback_errors.txt'), $errorMsg."\n", FILE_APPEND);
            return response()->json(['error' => $errorMsg]);
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
