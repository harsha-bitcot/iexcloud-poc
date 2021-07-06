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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/company/find/{ticker}', 'App\Http\Controllers\CompanyController@findCompany');

Route::get('/test/{ticker}', 'App\Http\Controllers\CompanyController@test');
Route::get('/market', 'App\Http\Controllers\CompanyController@market');
Route::get('/historic/{ticker}', 'App\Http\Controllers\CompanyController@historic');

