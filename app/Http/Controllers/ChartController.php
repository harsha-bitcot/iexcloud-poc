<?php

namespace App\Http\Controllers;

use App\Models\company;
use App\Models\dailyData;
use App\Utils\iexcloud;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChartController extends Controller
{
    public function __construct()
    {
        //
    }

    public function redirect(){
        dd('redirect');
    }

    public function token(){
        dd('token');
    }

    public function consent(){
        dd('consent');
    }

    public function triggerConsent(){
        $url = 'https://login.microsoftonline.com/common/adminconsent?client_id=a753a2b4-40bb-450d-a386-276436abaa9d&state=12345&redirect_uri=http://localhost/email/consent';
        $data =  Http::withToken('xhV_3cqLbm33Rcv-9tmp2rDAV0Gkbu.9.I')->get($url);
        return $data->json();
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

    public function getDummyData(){
        $data = company::find('TWTR')->data()->chronological()->get();
        $date = $data->pluck('date')->map(function ($item, $key) {
            return Carbon::createFromFormat('Y-m-d', $item)->isoFormat('x');
        });
        $open = $data->pluck('open');
        $high = $data->pluck('high');
        $low = $data->pluck('low');
        $close = $data->pluck('close');
        $volume = $data->pluck('volume');
        $result = array();
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

    public function index(){
        $data = company::find('TWTR')->data()->orderBy('date', 'asc')->get();
        return view('charts', ['data' => $data]);
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
