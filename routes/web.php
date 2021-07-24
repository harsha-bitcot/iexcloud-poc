<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('charts');
////    return view('welcome');
//});

Route::get('/email/redirect', 'App\Http\Controllers\ChartController@redirect');
Route::get('/email/token', 'App\Http\Controllers\ChartController@token');
Route::get('/email/consent', 'App\Http\Controllers\ChartController@consent');
Route::get('/email/consent/trigger', 'App\Http\Controllers\ChartController@triggerConsent');


Route::get('/dd', 'App\Http\Controllers\ChartController@getDummyData');
Route::get('/chart/{ticker}/OHLCV', 'App\Http\Controllers\ChartController@getOHLCV');

Route::get('/charts/{ticker}', 'App\Http\Controllers\ChartController@companyCharts');


Route::get('/', 'App\Http\Controllers\ChartController@index');
Route::get('/companies', 'App\Http\Controllers\CompanyController@list');
Route::get('/companies/status/toggle/{ticker}', 'App\Http\Controllers\CompanyController@toggleStatus');
Route::post('/companies/add', 'App\Http\Controllers\CompanyController@add');
Route::get('/companies/view/{ticker}', 'App\Http\Controllers\CompanyController@view');



Route::get('/company/find/{ticker}', 'App\Http\Controllers\CompanyController@findCompany');
Route::get('/company/add/{ticker}', 'App\Http\Controllers\CompanyController@addCompany');
Route::get('/data/add/{ticker}', 'App\Http\Controllers\CompanyController@addData');

Route::get('/test/{ticker}', 'App\Http\Controllers\CompanyController@test');
Route::get('/market', 'App\Http\Controllers\CompanyController@market');
Route::get('/historic/{ticker}', 'App\Http\Controllers\CompanyController@historic');

