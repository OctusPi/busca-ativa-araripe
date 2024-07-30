<?php

use Illuminate\Http\Request;
use App\Utils\Notify;
use App\Http\Controllers\Classes;
use App\Http\Controllers\Grids;
use App\Http\Controllers\Registrations;
use App\Http\Controllers\Reports;
use App\Http\Controllers\Series;
use App\Http\Controllers\Students;
use App\Http\Controllers\Subjects;
use App\Http\Controllers\Teachers;
use App\Http\Controllers\Home;
use App\Http\Controllers\Users;
use App\Http\Controllers\Organs;
use App\Http\Controllers\Schools;
use App\Http\Controllers\Frequencies;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

function common($prefix, $controller){
    Route::prefix($prefix)->controller($controller)->group(function () {
        Route::get('','index');
        Route::post('/save', 'save');
        Route::post('/list','list');
        Route::get('/details/{id}', 'details');
        Route::post('/delete','delete');
        Route::get('/selects/{key?}/{search?}', 'selects');
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

Route::middleware('auth:sanctum')->group(function () {
    common('/home', Home::class);
    common('/users', Users::class);
    common('/organs', Organs::class);
    common('/schools', Schools::class);
    common('/frequencies', Frequencies::class);
    common('/students', Students::class);
    common('/registrations', Registrations::class);
    common('/series', Series::class);
    common('/classes', Classes::class);
    common('/subjects', Subjects::class);
    common('/teachers', Teachers::class);
    common('/grids', Grids::class);
    common('/reports', Reports::class);

    
    Route::prefix('/students')->controller(Students::class)->group(function () {
        Route::post('/import','import');
    });
});

// fallback 404
Route::fallback(function () {
    return Response()->json(Notify::warning('Destino solicitado não existe...'), 404);
});

