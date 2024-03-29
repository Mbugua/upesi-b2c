<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('disburse','DisbursementController@disburse');
Route::post('result','DisbursementController@result');
Route::post('status','DisbursementController@status');
Route::post('reverse','DisbursementController@reverse');

Route::fallback('DisbursementController@notFound')->name('fallback');