<?php

use App\Http\Controllers\ApiController;
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
    return view('home');
});

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/user', [App\Http\Controllers\HomeController::class, 'user'])->name('user');
    Route::get('/user/show', [App\Http\Controllers\QuizController::class, 'show'])->name('user.show');

    Route::get('/quiz', [App\Http\Controllers\QuizController::class, 'index'])->name('quiz.start');
    Route::get('/quiz/game/{id}', [App\Http\Controllers\QuizController::class, 'game'])->name('quiz.game');
    Route::get('/quiz/new', [App\Http\Controllers\QuizController::class, 'newGame'])->name('quiz.new');
    Route::post('/quiz/save/{id}', [App\Http\Controllers\QuizController::class, 'store'])->name('quiz.store');

    Route::get('/linkedin', [ApiController::class, 'show'])->name('linkedin.index');
    Route::get('/linkedin/{$game_id}', [ApiController::class, 'index'])->name('linkedin');
    Route::get('/linkedin/add', [ApiController::class, 'store'])->name('linkedin.add');
});