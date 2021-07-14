<?php

namespace App\Http\Controllers;

use App\Models\company;
use App\Models\dailyData;
use App\Models\dataImport;
use Illuminate\Http\Request;
use App\Utils\iexcloud;

class CompanyController extends Controller
{
    public function __construct()
    {
        //
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
//        dd(iexcloud::getHistoricData($ticker, '1w'));
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
