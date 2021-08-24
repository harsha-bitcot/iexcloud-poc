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
        $companies = company::where('updateDailyData', true)->inRandomOrder()->limit(100)->get(); // 100 limit is due to iexcloud limits
        if ($companies->count() == 0){
            $this->info('No companies are marked for update');
            return true;
        }
        $tickers = $companies->pluck('symbol')->toArray();
        $batchData = iexcloud::BatchPreviousDayPrice($tickers);
        if ($batchData == null){
            $this->error('No data found with the given tickers');
            return false;
        }
        foreach ($companies as $company){
            for ($i=0;$i<count($batchData[$company->symbol]['chart']);$i++){
                try {
                    $data = $batchData[$company->symbol]['chart'][$i];
                } catch (Exception $e){
                    $this->error('Daily data could not be recorded for '. $company->symbol . ' because of un-availability in iexcloud');
                    continue;
                }
                try {
                    $dailyData = dailyData::firstOrNew(
                        ['company_symbol' => $data['symbol'], 'date' => $data['date']],
                        ['close' => $data['close'], 'high' => $data['high'], 'low' => $data['low'], 'open' => $data['open'], 'volume' => $data['volume'], 'changeOverTime' => $data['changeOverTime'], 'marketChangeOverTime' => $data['marketChangeOverTime'], 'uOpen' => $data['uOpen'], 'uClose' => $data['uClose'], 'uHigh' => $data['uHigh'], 'uLow' => $data['uLow'], 'uVolume' => $data['uVolume'], 'fOpen' => $data['fOpen'], 'fClose' => $data['fClose'], 'fHigh' => $data['fHigh'], 'fLow' => $data['fLow'], 'fVolume' => $data['fVolume'], 'change' => $data['change'], 'changePercent' => $data['changePercent']]
                    );
                    if ($dailyData->id === null){
                        $company->data()->save($dailyData);
                    } else{
                        $dailyData->close = $data['close'];
                        $dailyData->high = $data['high'];
                        $dailyData->low = $data['low'];
                        $dailyData->open = $data['open'];
                        $dailyData->volume = $data['volume'];
                        $dailyData->changeOverTime = $data['changeOverTime'];
                        $dailyData->marketChangeOverTime = $data['marketChangeOverTime'];
                        $dailyData->uOpen = $data['uOpen'];
                        $dailyData->uClose = $data['uClose'];
                        $dailyData->uHigh = $data['uHigh'];
                        $dailyData->uLow = $data['uLow'];
                        $dailyData->uVolume = $data['uVolume'];
                        $dailyData->fOpen = $data['fOpen'];
                        $dailyData->fClose = $data['fClose'];
                        $dailyData->fHigh = $data['fHigh'];
                        $dailyData->fLow = $data['fLow'];
                        $dailyData->fVolume = $data['fVolume'];
                        $dailyData->change = $data['change'];
                        $dailyData->changePercent = $data['changePercent'];
//                        dd($data);
//                        foreach ($data as $key=>$value){
//                            $dailyData->$key = $value;
//                        }
                        $dailyData->save();
                    }
                    if ($i == count($batchData[$company->symbol]['chart']) - 1){
                        $company->updateDailyData = false;
                        $company->save();
                    }
                } catch (Exception $e){
                    $this->error('Daily data could not be recorded for '. $company->symbol . ' because of:');
                    $this->error($e);
                    continue;
                }
                $this->info('Daily data successfully recorded for '. $company->symbol . ' on '. $dailyData->date);
            }
        }
        return true;
    }
}
