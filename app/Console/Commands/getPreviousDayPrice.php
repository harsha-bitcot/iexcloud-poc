<?php

namespace App\Console\Commands;

use Exception;
use App\Models\company;
use App\Models\dailyData;
use App\Utils\iexcloud;
use Illuminate\Console\Command;

class getPreviousDayPrice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'iex:previousDay';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get previous day data from iexcloud for a random client where updateDailyData is true and add it to database';

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
        $company = company::where('updateDailyData', true)->inRandomOrder()->first();
        if ($company == null){
            $this->info('No companies are marked for update');
            return true;
        }
        $data = iexcloud::previousDayPrice($company->symbol);
        if ($data == null){
            $this->error('No data found with the given ticker');
            return false;
        }
        try {
            $dailyData = dailyData::firstOrNew(
                ['company_symbol' => $data['symbol'], 'date' => $data['date']],
                ['close' => $data['close'], 'high' => $data['high'], 'low' => $data['low'], 'open' => $data['open'], 'volume' => $data['volume'], 'changeOverTime' => $data['changeOverTime'], 'marketChangeOverTime' => $data['marketChangeOverTime'], 'uOpen' => $data['uOpen'], 'uClose' => $data['uClose'], 'uHigh' => $data['uHigh'], 'uLow' => $data['uLow'], 'uVolume' => $data['uVolume'], 'fOpen' => $data['fOpen'], 'fClose' => $data['fClose'], 'fHigh' => $data['fHigh'], 'fLow' => $data['fLow'], 'fVolume' => $data['fVolume'], 'change' => $data['change'], 'changePercent' => $data['changePercent']]
            );
            $company->updateDailyData = false;
            $company->data()->save($dailyData);
            $company->save();
        } catch (Exception $e){
            $this->error($e);
            return false;
        }
        $this->info('Daily data successfully recorded for '. $company->symbol . ' on '. $dailyData->date);
        return true;
    }
}
