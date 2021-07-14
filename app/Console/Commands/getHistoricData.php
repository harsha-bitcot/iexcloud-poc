<?php

namespace App\Console\Commands;

use Exception;
use App\Models\dailyData;
use App\Models\dataImport;
use App\Utils\iexcloud;
use Illuminate\Console\Command;

class getHistoricData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'iex:historicData';

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
        $imports = dataImport::where('import', true)->oldest()->limit(4)->get();
        $this->info('Historic data job started for: '. $imports->pluck('company_symbol'));
        foreach ($imports as $import){
            $this->info('Started getting historic data for '. $import->company_symbol .' for the range '. $import->range);
            $batchData = iexcloud::getHistoricData($import->company_symbol, $import->range);
            if ($batchData == null){
                $this->error('No data found with the '. $import->company_symbol .' for '. $import->range);
                $import->import = false;
                $import->status = 'no data';
                $import->save();
                continue;
            }
            $success_count = 0;
            $fail_count = 0;
            foreach ($batchData as $data) {
                try {
                    $dailyData = dailyData::firstOrNew(
                        ['company_symbol' => $data['symbol'], 'date' => $data['date']],
                        ['close' => $data['close'], 'high' => $data['high'], 'low' => $data['low'], 'open' => $data['open'], 'volume' => $data['volume'], 'changeOverTime' => $data['changeOverTime'], 'marketChangeOverTime' => $data['marketChangeOverTime'], 'uOpen' => $data['uOpen'], 'uClose' => $data['uClose'], 'uHigh' => $data['uHigh'], 'uLow' => $data['uLow'], 'uVolume' => $data['uVolume'], 'fOpen' => $data['fOpen'], 'fClose' => $data['fClose'], 'fHigh' => $data['fHigh'], 'fLow' => $data['fLow'], 'fVolume' => $data['fVolume'], 'change' => $data['change'], 'changePercent' => $data['changePercent']]
                    );
                    $import->company()->first()->data()->save($dailyData);
                } catch (Exception $e){
                    $this->error('Data could not be recorded for '. $data['symbol'] . ' on '. $data['date'] .' because of:');
                    $this->error($e);
                    $fail_count++;
                    continue;
                }
                $success_count++;
                $this->info('Daily data successfully recorded for '. $dailyData->company_symbol . ' on '. $dailyData->date);
            }
            $import->import = false;
            $import->status = 'Finished importing, Success: '. $success_count . ', Failed: '. $fail_count;
            $import->save();
            $this->info('Finished getting historic data with the result Success: '. $success_count . ', Failed: '. $fail_count.' for : '. $import->company_symbol .' for the range '. $import->range);
        }
        return 0;
    }
}
