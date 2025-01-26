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

Route::group(['namespace'=>'\App\Http\Controllers\StoreFront','prefix'=>'sf','middleware'=>['api']],function(){
    Route::get('user/details', 'UserController@getUserDetails')->middleware('verifyusertoken');
    
    Route::get('get_exams', 'ExamController@getExams');
    Route::get('get_exams/{id}', 'ExamController@getExamById');

    Route::get('categories', 'CategoryController@getCategories'); // Get all categories
    Route::get('categories/tree', 'CategoryController@getCategoryTree'); // Get category tree
   
});


Route::group(['namespace'=>'\App\Http\Controllers\Admin','prefix'=>'admin','middleware'=>['api']],function(){
    Route::post('create_exams', 'ExamController@createExam');

    Route::post('create_category', 'CategoryController@store');
    Route::put('update_category/{id}', 'CategoryController@update');
});