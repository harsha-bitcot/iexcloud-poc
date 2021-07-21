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
//        dd($company->data()->previous52Weeks()->get()->groupBy(function ($val) {
//            $date = Carbon::parse($val->date);
//            return $date->startOfWeek()->isoFormat('MMM Do YY') . ' to '.$date->endOfWeek()->isoFormat('MMM Do YY');
////            return $date->year . ', '.$date->weekOfYear . ': ' . $date->startOfWeek()->toFormattedDateString() . ' to '.$date->endOfWeek()->toFormattedDateString();
//        })) ;// todo *******here
        $previousMonthData = $this->parseData($company->data()->previousMonth()->get());
        $previousFiftyTwoWeekData = $this->parseData($company->data()->previous52Weeks()->get());
//        dd($previousMonthData);
        return view('company.view', ['company' => $company,'previousMonthData'=>$previousMonthData,'previousFiftyTwoWeekData'=>$previousFiftyTwoWeekData]);
    }

    //todo check if this can be placed somewhere else
    private function parseData($collection): array
    {
        $collection = $collection->sortBy('date');
        $data = array();
        $data['date'] = $collection->pluck('date')->map(function ($item, $key) {
            return Carbon::createFromFormat('Y-m-d', $item)->toFormattedDateString();
        });
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
                $company->dataImport()->saveMany([
//                    new dataImport(['range' => 'H12020']),
//                    new dataImport(['range' => 'H22020']),
//                    new dataImport(['range' => 'H120201']),
                    new dataImport(['range' => 'H22021']),
                ]);
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

    public function addData($ticker){
        $company = company::find($ticker);
        if ($company == null){
            return 'no company found with given ticker';
        }
        $data = iexcloud::previousDayPrice($ticker);
//        $dailyData = new dailyData($data);
        $dailyData = dailyData::firstOrNew(
            ['company_symbol' => $data['symbol'], 'date' => $data['date']],
            ['close' => $data['close'], 'high' => $data['high'], 'low' => $data['low'], 'open' => $data['open'], 'volume' => $data['volume'], 'changeOverTime' => $data['changeOverTime'], 'marketChangeOverTime' => $data['marketChangeOverTime'], 'uOpen' => $data['uOpen'], 'uClose' => $data['uClose'], 'uHigh' => $data['uHigh'], 'uLow' => $data['uLow'], 'uVolume' => $data['uVolume'], 'fOpen' => $data['fOpen'], 'fClose' => $data['fClose'], 'fHigh' => $data['fHigh'], 'fLow' => $data['fLow'], 'fVolume' => $data['fVolume'], 'change' => $data['change'], 'changePercent' => $data['changePercent']]
        );
        $company->data()->save($dailyData);
        dd($company);
    }

    public function addCompany($ticker){
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
                $company->dataImport()->saveMany([
//                    new dataImport(['range' => 'H12020']),
//                    new dataImport(['range' => 'H22020']),
//                    new dataImport(['range' => 'H120201']),
                    new dataImport(['range' => 'H22021']),
                ]);
            }
        }
        dd($company);
    }

    public function findCompany($ticker)
    {
        dd(iexcloud::getCompanyByTicker($ticker));
    }

    public function test($ticker)
    {
//        dd(iexcloud::getHistoricData($ticker, '3d'));
//        dd(iexcloud::BatchPreviousDayPrice($ticker));
        dd(iexcloud::previousDayPrice($ticker));
    }

    public function market()
    {
        $result = iexcloud::previousDayMarket();
        dd($result);
    }

    public function historic($ticker)
    {
        dd(iexcloud::lastFiveDays($ticker));
    }
}
