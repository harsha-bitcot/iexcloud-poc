<?php

namespace App\Http\Controllers;

use App\Models\company;
use App\Models\dailyData;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class ChartController extends Controller
{
    public function __construct()
    {
        //
    }

    public function getClose($ticker){
        $result = array();
        $company = company::find($ticker);
        if ($company == null){
            return collect($result)->toJson();
        }
        $data = $company->data()->chronological()->get();
        $date = $data->pluck('date')->map(function ($item, $key) {
            return Carbon::createFromFormat('Y-m-d', $item)->startOfDay()->isoFormat('x');
        });
        $close = $data->pluck('close');
        $length = $date->count();
        for ($i=0; $i<$length; $i++){
            $result[$i][0] = (float)$date[$i];
            $result[$i][1] = (float)$close[$i];
        }
        return collect($result)->toJson();
    }

    public function getOHLCV($ticker)
    {
        $result = array();
        $company = company::find($ticker);
        if ($company == null){
            return collect($result)->toJson();
        }
        $data = $company->data()->chronological()->get();
        $date = $data->pluck('date')->map(function ($item, $key) {
            return Carbon::createFromFormat('Y-m-d', $item)->isoFormat('x');
        });
        $open = $data->pluck('open');
        $high = $data->pluck('high');
        $low = $data->pluck('low');
        $close = $data->pluck('close');
        $volume = $data->pluck('volume');
        $length = $date->count();
        for ($i=0; $i<$length; $i++){
            $result[$i][0] = (float)$date[$i];
            $result[$i][1] = (float)$open[$i];
            $result[$i][2] = (float)$high[$i];
            $result[$i][3] = (float)$low[$i];
            $result[$i][4] = (float)$close[$i];
            $result[$i][5] = (float)$volume[$i];
        }
        return collect($result)->toJson();
    }

    public function companyCharts($ticker){
        $company = company::find($ticker);
        if ($company == null){
            return redirect('/');
        }
        return view('chart.company', ['company' => $company]);
    }

    public function index(){
        $latestDate = dailyData::orderBy('date', 'desc')->first()->date;
        $topThree = dailyData::where('date', $latestDate)->orderBy('close', 'desc')->limit(3)->get()->pluck('company_symbol')->toArray();
        return view('chart.index', ['topThree' => $topThree]);
    }
}
