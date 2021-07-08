<?php

namespace App\Console\Commands;

use Exception;
use App\Models\company;
use App\Utils\iexcloud;
use Carbon\Carbon;
use Illuminate\Console\Command;

class checkForNewData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'iex:checkForNewData';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        //todo
        // think once about below comment ... check if running the cron 3 times per day suffices
        // setup a cache value to store the status of the day (to be expired in 6 hours)
        // starting from the 'previous days data' update time in iexcloud, setup a cron to run until next 6 hours
        // hit this every 2 hours until a satisfied response(db-updated) is saved in the cache
        $companies = company::where('status', true)->where('updateDailyData', 0)->inRandomOrder()->limit(5)->get();
        $this->info('Started checking for new daily data with: '. $companies->pluck('symbol'));
        $result = 'no-updates';
        foreach ($companies as $company){
            $data = iexcloud::previousDayPrice($company->symbol);
            if ($data == null){
                $result = 'iex-error';
                continue;
            }
            $iexDate = Carbon::createFromFormat('Y-m-d', $data['date']);
            $dbLatestEntry = $company->data()->latest()->first();
            $dbDate = $dbLatestEntry == null? $iexDate->subDay() : Carbon::createFromFormat('Y-m-d', $dbLatestEntry->date);
            if ($iexDate->isAfter($dbDate)){
                try {
                    company::where('status', true)->update(['updateDailyData' => true]);
                } catch (Exception $e){
                    $result = 'db-error';
                    continue;
                }
                $result = 'db-updated';
                break;
            }
        }
        $this->info('Checking for new daily data is done with the result '. $result);
        return $result;
    }
}
