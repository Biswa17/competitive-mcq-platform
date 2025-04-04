<?php

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

// Admin Routes
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    
    // Categories routes
    Route::get('/categories', function () {
        return view('admin.categories.index');
    })->name('admin.categories');
    
    // Exams routes
    Route::get('/exams', [\App\Http\Controllers\Admin\ExamController::class, 'index'])->name('admin.exams');
    Route::post('/exams', [\App\Http\Controllers\Admin\ExamController::class, 'store'])->name('admin.exams.store');
    
    // Add more admin routes here as needed
});
