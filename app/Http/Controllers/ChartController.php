<?php

namespace App\Http\Controllers;

use App\Models\company;
use App\Models\dailyData;
use App\Utils\iexcloud;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    public function __construct()
    {
        //
    }

    public function index(){
        $company = company::find('TWTR')->data()->orderBy('date', 'asc')->get();
        $date = $company->pluck('date');
        $changePercent = $company->pluck('changePercent');
        $close = $company->pluck('close');
        $high = $company->pluck('high');
        $low = $company->pluck('low');
        $open = $company->pluck('open');
        $change = $company->pluck('change');
        $range = array();
        for ($i=0; $i<$date->count(); $i++){
            array_push($range, [$low[$i], $high[$i]]);
        }
        return view('charts', ['date' => $date, 'changePercent' => $changePercent,
            'close' => $close,'range' => collect($range),'open' => $open,'change' => $change
        ]);
    }
}
