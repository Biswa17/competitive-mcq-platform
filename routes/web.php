<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ExamController;

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
    Route::get('/exams', [ExamController::class, 'index'])->name('admin.exams');
    Route::post('/exams', [ExamController::class, 'store'])->name('admin.exams.store');
    Route::get('/exams/{exam}/edit', [ExamController::class, 'edit'])->name('admin.exams.edit');
    Route::put('/exams/{exam}', [ExamController::class, 'update'])->name('admin.exams.update');
    Route::get('/exams/{exam}', [ExamController::class, 'show'])->name('admin.exams.show');
    Route::delete('/exams/{exam}/delete', [ExamController::class, 'destroy'])->name('admin.exams.destroy');
    
    // Add more admin routes here as needed
});
