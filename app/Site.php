<?php


namespace App;


use Carbon\Carbon;

class Site
{


    public static function getNetworks($type=null, $with_click=false)
    {
        if ($type) {
            $networks = Network::where('is_sms_callback', $type)->where('status', true);
        } else {
            $networks = Network::where('status', true);
        }

        if (!auth()->user()->isAdmin()) {
            $adminIds = User::where('is_admin', true)->pluck('id')->all();
            $networks = $networks->whereNotIn('user_id', $adminIds);
        }

        $networks = $networks->get();

        $response = [];

        $date = Carbon::now()->toDateString();

        foreach ($networks as $network) {
            if ($with_click) {
                $click = TotalClick::where('network_id', $network->id)->where('date', $date)->get();
                if ($click->count() > 0) {
                    $click = $click->first()->total;
                } else {
                    $click = 0;
                }
                $response[$network->id] = $network->name.' - Clicks Today : '.$click;
            } else {
                $response[$network->id] = $network->name;
            }

        }

        return $response;
    }


    public static function getConnection()
    {
        return Connection::pluck('name', 'id')->all();
    }


    public static function displayJson($ars)
    {
        $html = null;
        if (is_array($ars)) {
            foreach ($ars as $key => $ar) {
                $html .= '&nbsp;&nbsp;<b>'.$key.'</b>&nbsp;&nbsp;'.self::displayJson($ar).'<br/>';
            }
        } else {
            $html .= filter_var($ars, FILTER_VALIDATE_URL)? '<a href="'.$ars.'">Link</a>' : $ars;
        }

        return $html;
    }


}