<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use DB;
use Illuminate\Console\Command;

class ClearTraffic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:traffic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear Traffic 2 days ago';

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
            $date = Carbon::now()->subDays(2)->format('YmdHi');

            DB::table('traffics')->where('minute', '<', intval($date))->delete();

            $this->line(intval($date));


            $this->line( 'Clear Traffic 2 days ago');
        } catch (\Exception $e) {

            $this->line($e->getMessage());
        }
    }
}
