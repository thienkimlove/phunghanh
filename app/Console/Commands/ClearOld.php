<?php

namespace App\Console\Commands;

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

        DB::beginTransaction();

        $now = Carbon::now()->timestamp;

        $time = Carbon::now()->subDays(5)->toDateTimeString();

        try {

            DB::statement("DROP TABLE IF EXISTS temp_network_clicks;");
            DB::statement("create table temp_network_clicks like network_clicks;");
            DB::statement("ALTER TABLE temp_network_clicks CHANGE id id INT(10) UNSIGNED NOT NULL;");
            DB::statement("INSERT INTO temp_network_clicks select * from `network_clicks` where `log_callback_url` is not null or `created_at` > '".$time."';");
            DB::statement("ALTER TABLE temp_network_clicks CHANGE id id INT(10) AUTO_INCREMENT;");
            DB::statement("RENAME TABLE network_clicks TO backup_network_clicks_".$now);
            DB::statement("RENAME TABLE temp_network_clicks TO network_clicks");


            DB::commit();
            $this->line( 'Old clicks not have callback for 1 week clear!');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->line($e->getMessage());
        }
    }
}
