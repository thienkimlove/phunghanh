<?php

namespace App\Console\Commands;

use App\NetworkClick;
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

            NetworkClick::where('is_lead', false)->delete();
            $this->line( 'Old clicks not have callback for 1 week clear!');
        } catch (\Exception $e) {

            $this->line($e->getMessage());
        }
    }
}
