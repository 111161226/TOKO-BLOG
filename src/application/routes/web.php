<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\UserController;

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

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

Route::post('/login', [LoginController::class, 'login']);

Route::get('/register', [RegisterController::class, 'showRegistrationForm']);

Route::post('/register', [RegisterController::class, 'register']);

Route::middleware(['auth'])->group(function () {
    Route::get('/', [ImageController::class, 'index']);

    Route::resource('images', ImageController::class)->only([
        'index', 'show', 'store', 'destroy'
    ]);

    Route::get('/profile', [UserController::class, 'show'])->name('user.show');
    
    Route::patch('/profile', [UserController::class, 'update'])->name('user.update');
    
    Route::resource('blog', BlogController::class);

    Route::get('/search', function() {
        return view('searchblog');
    });

    Route::post('/search', function() {
        return view('searchblog');
    });

    Route::delete('/logout', [LoginController::class, 'logout'])->name('logout');
});