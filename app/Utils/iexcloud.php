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
//https://cloud.iexapis.com/stable/stock/market/batch?symbols=aapl,fb,tsla&types=chart&last=1&token=pk_2829dbd4b3884122a5bbbf95710199ef
// dd($data['AAPL']['chart'][0]['date']);
//  todo  return $data->failed();

class iexcloud
{
    public static function getCompanyByTicker($ticker){
        $url = 'https://cloud.iexapis.com/stable/stock/'. $ticker .'/company?token='. ENV('IEXCLOUD_API_KEY');
        $data =  Http::get($url);
        return $data->json();
    }

    public static function getHistoricData($ticker, $range){
        $url = 'https://cloud.iexapis.com/stable/stock/'. $ticker .'/chart/'. $range .'?token='. ENV('IEXCLOUD_API_KEY');
        $data =  Http::get($url);
        return $data->json();
    }

    public static function previousDayPrice($ticker){
        $url = 'https://cloud.iexapis.com/stable/stock/'. $ticker .'/previous?token='. ENV('IEXCLOUD_API_KEY');
        $data =  Http::get($url);
        return $data->json();
    }

    public static function BatchPreviousDayPrice($tickers){
        $tickers = gettype($tickers) == 'string'? $tickers : implode(",",$tickers);
        $url = 'https://cloud.iexapis.com/stable/stock/market/batch?symbols='. $tickers .'&types=chart&last=1&token='. ENV('IEXCLOUD_API_KEY');
        $data =  Http::get($url);
        return $data->json();
    }

    public static function previousDayMarket(){
        return null; // temp disabling this credit guzzler
        $url = 'https://cloud.iexapis.com/stable/stock/market/previous?token='. ENV('IEXCLOUD_API_KEY');
        $data =  Http::get($url);
        return $data->json();
    }

    public static function lastFiveDays($ticker){
        $url = 'https://cloud.iexapis.com/stable/stock/'. $ticker .'/chart/5d?token='. ENV('IEXCLOUD_API_KEY');
        $data =  Http::get($url);
        return $data->json();
    }
}
