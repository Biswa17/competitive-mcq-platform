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
    Route::post('logout', 'AuthController@logout')->middleware('verifyusertoken')->name('logout');
    Route::post('generate_otp', 'AuthController@generateOtp');
    Route::post('verify_otp', 'AuthController@verifyOtp');
    Route::post('register_details', 'UserController@registerUser')->middleware('verifyusertoken')->name('register_details');
});

Route::group(['namespace'=>'\App\Http\Controllers\StoreFront','prefix'=>'user','middleware'=>['api']],function(){
    Route::get('details', 'UserController@getUserDetails')->middleware('verifyusertoken');
});
