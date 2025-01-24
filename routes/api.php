<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::group(['namespace'=>'\App\Http\Controllers\Auth','prefix'=>'auth','middleware'=>['api']],function(){
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout')->middleware('verifyusertoken');

    Route::post('generate_otp', 'AuthController@generateOtp');
    Route::post('verify_otp', 'AuthController@verifyOtp');
});

