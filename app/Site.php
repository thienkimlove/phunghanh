<?php


namespace App;


use Carbon\Carbon;

class Site
{

    public static function fillLinkIdToNetwork($network)
    {
        $network_urls = json_decode($network->click_url, true);

        $new_data = [];

        foreach ($network_urls as $network_url) {

            if (isset($network_url['click_url']) && $network_url['click_url']) {
                $check_exits = Link::where('network_id', $network->id)->where('link', $network_url['click_url'])->get();

                if ($check_exits->count() > 0) {
                    $network_url['link_id'] = $check_exits->first()->id;
                } else {
                    $insert = Link::create([
                        'network_id' => $network->id,
                        'link' => $network_url['click_url']
                    ]);
                    $network_url['link_id'] = $insert->id;
                }
            }
            $new_data[] = $network_url;
        }

        $network->update([
            'click_url' => json_encode($new_data, true)
        ]);
    }

    public static function getNetworkLinks($network)
    {
        return json_decode($network->click_url, true);

    }


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

                # get total clicks by link
                $click_by_link = null;

                $network_urls = json_decode($network->click_url, true);

                $start_of_date = Carbon::now()->startOfDay()->format('YmdHi');
                $end_of_date = Carbon::now()->endOfDay()->format('YmdHi');

                $i = 0;

                foreach ($network_urls as $network_url) {

                    if (isset($network_url['link_id']) && $network_url['link_id']) {
                        $click_by_link .= '| Link '.$i.' : ';
                        $clicks = Traffic::where('network_id', $network->id)
                            ->where('link_id', $network_url['link_id'])
                            ->whereBetween('minute', [$start_of_date, $end_of_date])
                            ->get()
                            ->sum('click');
                        $click_by_link .= $clicks;
                    }

                    $i++;
                }
                $response[$network->id] = $network->name.' - Clicks Today : '.$click.$click_by_link;
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