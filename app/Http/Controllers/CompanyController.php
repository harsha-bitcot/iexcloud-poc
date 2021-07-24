<?php

namespace App\Http\Controllers;

use App\Models\company;
use App\Models\dailyData;
use App\Models\dataImport;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Utils\iexcloud;

class CompanyController extends Controller
{
    public function __construct()
    {
        //
    }

    Public function list(){
        $companies = company::orderBy('updated_at', 'desc')->get();
        return view('company.list', ['companies' => $companies]);
    }

    public function view($ticker){
        $company = company::find($ticker);
        if ($company == null){
            return redirect('/companies');
        }
        $previousMonthData = $this->parseData($company->data()->previousMonth()->get());
        $fiftyTwoWeek = $company->data()->previous52Weeks();
        $previousFiftyTwoWeekData = $this->parseData($fiftyTwoWeek->get());
        $weeklyFiftyTwoWeekData = $this->parseData($this->prepareConsolidatedData($fiftyTwoWeek->getWeeklyConsolidated()));
        $monthlyFiftyTwoWeekData = $this->parseData($this->prepareConsolidatedData($fiftyTwoWeek->getMonthlyConsolidated()));
//        dd($previousFiftyTwoWeekData['high']->max());
        return view('company.view', ['company' => $company,'previousMonthData'=>$previousMonthData,'previousFiftyTwoWeekData'=>$previousFiftyTwoWeekData,
            'weeklyFiftyTwoWeekData'=>$weeklyFiftyTwoWeekData,'monthlyFiftyTwoWeekData'=>$monthlyFiftyTwoWeekData]);
    }

    private function prepareConsolidatedData($collection): \Illuminate\Support\Collection
    {
        $result = array();
        foreach ($collection as $key=>$value){
            $data = array();
            $data['date'] = $key;
            $data['changePercent'] = $value->pluck('changePercent')->sum() + $value->pluck('changePercent')->reduce(function ($carry, $item) {
                    return $carry * $item;
                });
            $data['close'] = $value->pluck('close')->last();
            $data['high'] = $value->pluck('high')->max();
            $data['low'] = $value->pluck('low')->min();
            $data['open'] = $value->pluck('open')->first();
            $data['change'] = $value->pluck('change')->sum();
            array_push($result, collect($data));
        }
        return collect($result);
    }

    private function parseData($collection): array
    {
        $data = array();
        if ($collection->count() == 0){
            return $data;
        }
        $collection = $collection->sortBy('date');

        $data['date'] = Carbon::hasFormat($collection->pluck('date')[0],'Y-m-d') ? $collection->pluck('date')->map(function ($item, $key) {
            return Carbon::createFromFormat('Y-m-d', $item)->toFormattedDateString();
        }) : $collection->pluck('date');

        $data['changePercent'] = $collection->pluck('changePercent');
        $data['close'] = $collection->pluck('close');
        $data['high'] = $collection->pluck('high');
        $data['low'] = $collection->pluck('low');
        $data['open'] = $collection->pluck('open');
        $data['change'] = $collection->pluck('change');
        $range = array();
        for ($i=0; $i<$data['date']->count(); $i++){
            array_push($range, [$data['low'][$i], $data['high'][$i]]);
        }
        $changeCount = [0, 0];
        $changeValue = [0, 0];
        foreach ($data['change'] as $value){
            if ($value>0){
                $changeCount[0]++;
                $changeValue[0] += $value;
            }else{
                $changeCount[1]++;
                $changeValue[1] += $value;
            }
        }
        $data['range'] = collect($range);
        $data['changeCount'] = collect($changeCount);
        $data['changeValue'] = collect($changeValue);
        return $data;
    }

    public function add(Request $request){
        $this->validate(request(),[ // check for more validation rules
            'ticker' => 'required'
        ]);
        $ticker = $request->input('ticker');
        $company = company::find($ticker);
        if ($company == null){
            $company = iexcloud::getCompanyByTicker($ticker);
            if ($company != null){
                $company = company::create([
                    'symbol' => $company['symbol'],
                    'companyName' => $company['companyName'],
                    'exchange' => $company['exchange'],
                    'industry' => $company['industry'],
                    'website' => $company['website'],
                    'description' => $company['description'],
                    'sector' => $company['sector'],
                    'city' => $company['city'],
                    'state' => $company['state'],
                    'country' => $company['country']
                ]);
                $companyList = array("AAPL", "AMZN", "TWTR", "MSFT", "FB", "GOOG", "GOOGL", "TSLA");
                if (in_array($company['symbol'], $companyList)) {
                    $company->dataImport()->saveMany([
                        new dataImport(['range' => 'H12020']),
                        new dataImport(['range' => 'H22020']),
                        new dataImport(['range' => 'H12021']),
                        new dataImport(['range' => 'H22021']),
                    ]);
                }else{
                    $company->dataImport()->saveMany([
                        new dataImport(['range' => '1w'])
                    ]);
                }
            }
        }
        return redirect('/companies');
    }

    Public function toggleStatus($ticker){
        $company = company::find($ticker);
        if ($company != null){
            $company->status = !$company->status;
            $company->save();
        }
        return redirect('/companies');
    }

//    public function addData($ticker){
//        $company = company::find($ticker);
//        if ($company == null){
//            return 'no company found with given ticker';
//        }
//        $data = iexcloud::previousDayPrice($ticker);
////        $dailyData = new dailyData($data);
//        $dailyData = dailyData::firstOrNew(
//            ['company_symbol' => $data['symbol'], 'date' => $data['date']],
//            ['close' => $data['close'], 'high' => $data['high'], 'low' => $data['low'], 'open' => $data['open'], 'volume' => $data['volume'], 'changeOverTime' => $data['changeOverTime'], 'marketChangeOverTime' => $data['marketChangeOverTime'], 'uOpen' => $data['uOpen'], 'uClose' => $data['uClose'], 'uHigh' => $data['uHigh'], 'uLow' => $data['uLow'], 'uVolume' => $data['uVolume'], 'fOpen' => $data['fOpen'], 'fClose' => $data['fClose'], 'fHigh' => $data['fHigh'], 'fLow' => $data['fLow'], 'fVolume' => $data['fVolume'], 'change' => $data['change'], 'changePercent' => $data['changePercent']]
//        );
//        $company->data()->save($dailyData);
//        dd($company);
//    }

//    public function addCompany($ticker){
//        $company = company::find($ticker);
//        if ($company == null){
//            $company = iexcloud::getCompanyByTicker($ticker);
//            if ($company != null){
//                $company = company::create([
//                    'symbol' => $company['symbol'],
//                    'companyName' => $company['companyName'],
//                    'exchange' => $company['exchange'],
//                    'industry' => $company['industry'],
//                    'website' => $company['website'],
//                    'description' => $company['description'],
//                    'sector' => $company['sector'],
//                    'city' => $company['city'],
//                    'state' => $company['state'],
//                    'country' => $company['country']
//                ]);
//                $company->dataImport()->saveMany([
////                    new dataImport(['range' => 'H12020']),
////                    new dataImport(['range' => 'H22020']),
////                    new dataImport(['range' => 'H120201']),
//                    new dataImport(['range' => 'H22021']),
//                ]);
//            }
//        }
//        dd($company);
//    }

//    public function findCompany($ticker)
//    {
//        dd(iexcloud::getCompanyByTicker($ticker));
//    }

//    public function test($ticker)
//    {
////        dd(iexcloud::getHistoricData($ticker, '3d'));
//        dd(iexcloud::BatchPreviousDayPrice($ticker));
//        dd(iexcloud::previousDayPrice($ticker));
//    }

//    public function market()
//    {
//        $result = iexcloud::previousDayMarket();
//        dd($result);
//    }

//    public function historic($ticker)
//    {
//        dd(iexcloud::lastFiveDays($ticker));
//    }
}
