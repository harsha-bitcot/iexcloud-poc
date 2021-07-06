<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utils\iexcloud;

class CompanyController extends Controller
{
    public function __construct()
    {
        //
    }

    public function findCompany($ticker)
    {
        dd(iexcloud::getCompanyByTicker($ticker));
    }

    public function test($ticker)
    {
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
