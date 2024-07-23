<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

function common($prefix, $controller){
    Route::prefix($prefix)->controller($controller)->group(function () {
        Route::get('/','index');
        Route::post('/save', 'save');
        Route::post('/list','list');
        Route::get('/details', 'details');
        Route::post('/delete','delete');
    });
}

Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('/login','login');
    Route::get('/logout','logout');
    Route::get('/check','check');
    Route::post('/recover','recover');
    Route::get('/validate_renew/{token}', 'validate_renew');
    Route::post('/renew', 'renew');
});
