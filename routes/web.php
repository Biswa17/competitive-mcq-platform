<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TopicController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\QuestionPaperController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingsController;

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
    // If user is already logged in, redirect to admin dashboard
    if (session()->has('admin_token')) {
        return redirect()->route('admin.dashboard');
    }
    return view('login');
});

// Admin Auth Routes
Route::post('/admin/login', [App\Http\Controllers\Auth\AuthController::class, 'adminLogin'])->name('admin.login');
Route::get('/admin/logout', function() {
    session()->forget('admin_token');
    return redirect('/')->with('success', 'You have been logged out successfully.');
})->name('admin.logout');

// Admin Routes
Route::prefix('admin')->middleware('admin.auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
    
    // Categories routes
    Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories');
    Route::post('/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');
    
    // Exams routes
    Route::get('/exams', [ExamController::class, 'index'])->name('admin.exams');
    Route::post('/exams', [ExamController::class, 'store'])->name('admin.exams.store');
    Route::get('/exams/{exam}/edit', [ExamController::class, 'edit'])->name('admin.exams.edit');
    Route::put('/exams/{exam}', [ExamController::class, 'update'])->name('admin.exams.update');
    Route::get('/exams/{exam}', [ExamController::class, 'show'])->name('admin.exams.show');
    Route::delete('/exams/{exam}/delete', [ExamController::class, 'destroy'])->name('admin.exams.destroy');
    
    // Topics routes
    Route::get('/topics', [TopicController::class, 'index'])->name('admin.topics');
    Route::post('/topics', [TopicController::class, 'store'])->name('admin.topics.store');
    Route::get('/topics/{topic}', [TopicController::class, 'show'])->name('admin.topics.show');
    Route::get('/topics/{topic}/edit', [TopicController::class, 'edit'])->name('admin.topics.edit');
    Route::put('/topics/{topic}', [TopicController::class, 'update'])->name('admin.topics.update');
    Route::delete('/topics/{topic}', [TopicController::class, 'destroy'])->name('admin.topics.destroy');
    
    // Questions routes
    Route::get('/questions', [QuestionController::class, 'index'])->name('admin.questions');
    Route::get('/questions/create', [QuestionController::class, 'create'])->name('admin.questions.create');
    Route::post('/questions', [QuestionController::class, 'store'])->name('admin.questions.store');
    Route::get('/questions/{question}', [QuestionController::class, 'show'])->name('admin.questions.show');
    Route::get('/questions/{question}/edit', [QuestionController::class, 'edit'])->name('admin.questions.edit');
    Route::put('/questions/{question}', [QuestionController::class, 'update'])->name('admin.questions.update');
    Route::delete('/questions/{question}', [QuestionController::class, 'destroy'])->name('admin.questions.destroy');
    
    // Question Papers routes
    Route::get('/question-papers', [QuestionPaperController::class, 'index'])->name('admin.question-papers');
    Route::get('/question-papers/create', [QuestionPaperController::class, 'create'])->name('admin.question-papers.create');
    Route::post('/question-papers', [QuestionPaperController::class, 'store'])->name('admin.question-papers.store');
    Route::get('/question-papers/{questionPaper}', [QuestionPaperController::class, 'show'])->name('admin.question-papers.show');
    Route::get('/question-papers/{questionPaper}/edit', [QuestionPaperController::class, 'edit'])->name('admin.question-papers.edit');
    Route::put('/question-papers/{questionPaper}', [QuestionPaperController::class, 'update'])->name('admin.question-papers.update');
    Route::delete('/question-papers/{questionPaper}', [QuestionPaperController::class, 'destroy'])->name('admin.question-papers.destroy');
    Route::post('/question-papers/{questionPaper}/add-questions', [QuestionPaperController::class, 'addQuestions'])->name('admin.question-papers.add-questions');
    Route::delete('/question-papers/{questionPaper}/questions/{question}', [QuestionPaperController::class, 'removeQuestion'])->name('admin.question-papers.remove-question');
    
    // Users routes
    Route::get('/users', [UserController::class, 'index'])->name('admin.users');
    Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('admin.users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    
    // Settings routes
    Route::get('/settings', [SettingsController::class, 'index'])->name('admin.settings');
    Route::put('/settings', [SettingsController::class, 'update'])->name('admin.settings.update');
});
