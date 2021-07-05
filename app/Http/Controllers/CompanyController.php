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

    public function test($ticker)
    {
        dd(iexcloud::previousDayPrice($ticker));
    }
    public function historic($ticker)
    {
        dd(iexcloud::lastFiveDays($ticker));
    }
}
