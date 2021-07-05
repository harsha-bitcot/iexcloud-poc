<?php

namespace App\Utils;

use Illuminate\Support\Env;
use Illuminate\Support\Facades\Http;

// https://cloud.iexapis.com/stable/stock/twtr/chart/date/20190109?token=
// https://cloud.iexapis.com/stable/stock/twtr/chart/5m?token=
// https://cloud.iexapis.com/stable/stock/twtr/previous?token=
// https://cloud.iexapis.com/stable/stock/market/previous?token=
// https://cloud.iexapis.com/stable/ref-data/exchange/nys/symbols?token=
// https://iexcloud.io/docs/api/#company
//  todo  return $data->failed();

class iexcloud
{
    public static function previousDayPrice($ticker){
        $url = 'https://cloud.iexapis.com/stable/stock/'. $ticker .'/previous?token='. ENV('IEXCLOUD_API_KEY');
        $data =  Http::get($url);
        return $data->json();
    }

    public static function lastFiveDays($ticker){
        $url = 'https://cloud.iexapis.com/stable/stock/'. $ticker .'/chart/5d?token='. ENV('IEXCLOUD_API_KEY');
        $data =  Http::get($url);
        return $data->json();
    }
}
