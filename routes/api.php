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
    // Open API Routes (Allowing Guest Access where needed)
    Route::get('get_exams', 'ExamController@getExams');
    Route::get('get_exam/{id}', 'ExamController@getExamById')->middleware('allow.guest'); // Apply AllowGuestMiddleware
    Route::get('get_popular_exams', 'ExamController@getPopularExams');

    Route::get('categories', 'CategoryController@index'); // Get all categories
    Route::get('categories/tree', 'CategoryController@getCategoryTree'); // Get category tree
    Route::get('categories/{id}', 'CategoryController@show'); // Get category by ID with subcategories
    
    
    // Protected Routes
    Route::group(['middleware' => ['verifyusertoken']], function () {
        Route::get('user/details', 'UserController@getUserDetails');
        Route::get('questions/topic/{id}', 'QuestionController@getQuestionsByTopic');
        Route::get('questions/question-paper/{id}', 'QuestionController@getQuestionsByQuestionPaper'); // New route for question paper
        
        // Store user answers by topic
        Route::post('questions/user-answer-by-topic', 'QuestionController@storeUserAnswerByTopic'); // Renamed route and controller method

        // Store user answers for question paper
        Route::post('questions/user-answer-for-question-paper', 'QuestionController@storeUserAnswerForQuestionPaper'); // New route

    });
});



Route::group(['namespace'=>'\App\Http\Controllers\Admin','prefix'=>'admin','middleware'=>['api']],function(){
    Route::post('create_exams', 'ExamController@createExam');
    Route::get('exams', 'ExamController@getExams'); 
    Route::get('exams/{id}', 'ExamController@getExamById');
    Route::put('exams/{id}', 'ExamController@updateExam');
    
    Route::post('create_category', 'CategoryController@store');
    Route::put('update_category/{id}', 'CategoryController@update');
    
    // Question Paper CRUD routes
    Route::apiResource('question_papers', 'QuestionPaperController');
    Route::post('question_papers/analyze', 'QuestionPaperController@analyzeFile');
    // Exam management routes
   
});
