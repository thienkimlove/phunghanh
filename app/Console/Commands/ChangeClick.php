<?php

namespace App\Console\Commands;

use App\Link;
use App\Network;
use App\Site;
use Illuminate\Console\Command;

class ChangeClick extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'change:click';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change format of click_url field network';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $networks = Network::all();
        foreach ($networks as $network) {

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
    }
}
