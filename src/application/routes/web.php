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

Route::group(['middleware' => ['guest']], function () {
    Route::get('/home', function(){
        return view('home');
    });
    
    Route::post('/home', function() {
        return view('upload');
    });
    
    Route::get('/image', function() {
        return view('image');
    });
    
    Route::get('/remove', function() {
        return view('delete');
    });
    
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
});

Route::get('/login', function() {
    return view('login');
});

Route::get('/signup', function() {
    return view('signup');
});

Route::post('/login', function() {
    return view('getuser');
});

Route::post('/signup', function() {
    return view('register');
});