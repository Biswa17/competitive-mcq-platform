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


Route::group(['namespace' => '\App\Http\Controllers\Auth', 'prefix' => 'auth', 'middleware' => ['api']], function () {
    Route::post('generate_otp', 'AuthController@generateOtp'); // Open API
    Route::post('verify_otp', 'AuthController@verifyOtp'); // Open API

    Route::group(['middleware' => ['verifyusertoken']], function () {
        Route::post('logout', 'AuthController@logout')->name('logout');
        Route::post('register_details', 'UserController@registerUser')->name('register_details');
    });
});

Route::group(['namespace' => '\App\Http\Controllers\StoreFront', 'prefix' => 'sf', 'middleware' => ['api']], function () {
    // Open API Routes
    Route::get('get_exams', 'ExamController@getExams');
    Route::get('get_exam/{id}', 'ExamController@getExamById');
    Route::get('get_popular_exams', 'ExamController@getPopularExams');

    Route::get('categories', 'CategoryController@index'); // Get all categories
    Route::get('categories/tree', 'CategoryController@getCategoryTree'); // Get category tree
    Route::get('categories/{id}', 'CategoryController@show'); // Get category by ID with subcategories
    
    
    // Protected Routes
    Route::group(['middleware' => ['verifyusertoken']], function () {
        Route::get('user/details', 'UserController@getUserDetails');
        Route::get('questions/topic/{id}', 'QuestionController@getQuestionsByTopic');
        
        // Store user answers
        Route::post('questions/user-answer', 'QuestionController@storeUserAnswer');

    });
});



Route::group(['namespace'=>'\App\Http\Controllers\Admin','prefix'=>'admin','middleware'=>['api']],function(){
    Route::post('create_exams', 'ExamController@createExam');

    Route::post('create_category', 'CategoryController@store');
    Route::put('update_category/{id}', 'CategoryController@update');
});