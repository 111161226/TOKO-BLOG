<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ImageController;

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

Route::delete('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm']);

Route::post('/register', [RegisterController::class, 'register']);

Route::middleware(['auth'])->group(function () {
    Route::get('/', [ImageController::class, 'index']);

    Route::resource('images', ImageController::class)->only([
        'index', 'show', 'store', 'destroy'
    ]);
    
    Route::resource('blog', BlogController::class);
    
    Route::get('/mblog', function() {
        return view('makeblog');
    });

    Route::post('/mblog', function() {
        return view('createblog');
    });

    Route::get('/lblog', function() {
        return view('bloglist');
    });

    Route::get('/sblog', function() {
        return view('showblog');
    });

    Route::get('/dblog', function() {
        return view('deleteblog');
    });

    Route::get('/eblog', function() {
        return view('editblog');
    });

    Route::post('/eblog', function() {
        return view('updateblog');
    });

    Route::get('/search', function() {
        return view('searchblog');
    });

    Route::post('/search', function() {
        return view('searchblog');
    });

    Route::get('/logout', function() {
        return view('logout');
    });

    Route::get('/profile', function() {
        return view('profile');
    });

    Route::post('/profile', function() {
        return view('uprofile');
    });

    Route::get('/thumnail', function() {
        return view('thumnail');
    });
});