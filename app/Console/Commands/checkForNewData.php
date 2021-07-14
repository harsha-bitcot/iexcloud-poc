<?php

namespace App\Console\Commands;

use Exception;
use App\Models\company;
use App\Utils\iexcloud;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
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
            $dbDate = $dbLatestEntry == null? $iexDate->copy()->subDay() : Carbon::createFromFormat('Y-m-d', $dbLatestEntry->date);
            if ($iexDate->isAfter($dbDate)){
                try {
                    company::where('status', true)->update(['updateDailyData' => true]);
                } catch (Exception $e){
                    $result = 'db-error';
                    continue;
                }
                $result = 'db-updated';
                Cache::put('iexDailyUpdated', true, now()->addHours(8));
                break;
            }
        }
        $this->info('Checking for new daily data is done with the result '. $result);
        return $result;
    }
}
