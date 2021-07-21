<?php

namespace App\Http\Controllers;

use App\Models\company;
use App\Models\dailyData;
use App\Utils\iexcloud;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    public function __construct()
    {
        //
    }

    public function index(){
        $company = company::find('TWTR')->data()->orderBy('date', 'asc')->get();
        $date = $company->pluck('date')->map(function ($item, $key) {
            return Carbon::createFromFormat('Y-m-d', $item)->toFormattedDateString();
        });
        $changePercent = $company->pluck('changePercent');
        $close = $company->pluck('close');
        $high = $company->pluck('high');
        $low = $company->pluck('low');
        $open = $company->pluck('open');
        $change = $company->pluck('change');
        $newChangePercent = $changePercent->map(function ($item, $key) {
            return $item + rand(0,5);
        });
        $changeCount = [0, 0];
        $changeValue = [0, 0];
        foreach ($change as $value){
            if ($value>0){
                $changeCount[0]++;
                $changeValue[0] += $value;
            }else{
                $changeCount[1]++;
                $changeValue[1] += $value;
            }
        }
        $range = array();
        for ($i=0; $i<$date->count(); $i++){
            array_push($range, [$low[$i], $high[$i]]);
        }
        return view('charts', ['date' => $date, 'changePercent' => $changePercent,
            'close' => $close,'range' => collect($range),'open' => $open,'change' => $change,'newChangePercent' => $newChangePercent,
            'changeValue' => collect($changeValue), 'changeCount' => collect($changeCount)
        ]);
    }
}
