<?php

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

Route::get('/manage/currency', 'Currency\CurrencyConverterController@index');

Auth::routes();

Route::get('/home', 'Currency\CurrencyConverterController@rateCreate');

// To Adding new currency.
Route::post('/currency/add', 'Currency\CurrencyConverterController@store');
Route::get('/currency/add', 'Currency\CurrencyConverterController@create');

// To Adding new Currency Rate.
Route::post('/currency/rate/add', 'Currency\CurrencyConverterController@rateStore');
Route::post('/currency/rate/check', 'Currency\CurrencyConverterController@rateCheck');
Route::post('/currency/rate/get', 'Currency\CurrencyConverterController@getRate');

// To Check the currency is existing or not.
Route::get('/currency/check/{code}', 'Currency\CurrencyConverterController@checkCurrency');