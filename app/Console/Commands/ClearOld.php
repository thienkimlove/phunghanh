<?php

namespace App\Console\Commands;

use App\Network;
use App\NetworkClick;
use App\TotalClick;
use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;

class ClearOld extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:old';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear old click not have callback hoac truoc 1 tuan';

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
        //clear no lead offers.

        try {
            $date = Carbon::now()->toDateString();
            $networks = Network::where('status', true)->whereIn('is_sms_callback', [1,2])->get();

            foreach ($networks as $network) {
                $affected = NetworkClick::where('is_lead', false)->where('network_id', $network->id)->delete();
                $total = TotalClick::where('network_id', $network->id)->where('date', $date)->get();
                if ($total->count() > 0) {
                    $total = $total->first();
                    $total->increment('total', $affected);
                    $total->save();
                } else {
                    TotalClick::create([
                        'network_id' => $network->id,
                        'date' => $date,
                        'total' => $affected
                    ]);
                }
            }

            $this->line( 'Old clicks not have callback for 1 week clear!');
        } catch (\Exception $e) {

            $this->line($e->getMessage());
        }
    }
}
