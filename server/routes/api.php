<?php

use App\Http\Controllers\Authentication;
use App\Http\Controllers\Classes;
use App\Http\Controllers\Frequencies;
use App\Http\Controllers\Grids;
use App\Http\Controllers\Organs;
use App\Http\Controllers\Registrations;
use App\Http\Controllers\Schools;
use App\Http\Controllers\Series;
use App\Http\Controllers\Students;
use App\Http\Controllers\Subjects;
use App\Http\Controllers\Teachers;
use App\Http\Controllers\Users;
use App\Utils\Notify;
use Illuminate\Support\Facades\Route;


function commonRoutes($prefix, $controller){
    Route::prefix($prefix)->controller($controller)->group(function () {
        Route::post('/save', 'save');
        Route::put('/update', 'update');
        Route::get('/details/{id}', 'details');
        Route::post('/list', 'list');
    });
}


// open routes
Route::prefix('/auth')->controller(Authentication::class)->group(function () {
    Route::post('/login', 'login');
    Route::get('/logout/{id}', 'logout');
    Route::get('/active', 'active');
    Route::post('/recover', 'recover');
    Route::post('/renew', 'renew');
});

// protected routes
Route::middleware('auth:sanctum')->group(function () {

    // call common routes
    commonRoutes('organs', Organs::class);
    commonRoutes('schools', Schools::class);
    commonRoutes('classes', Classes::class);
    commonRoutes('frequencies', Frequencies::class);
    commonRoutes('grids', Grids::class);
    commonRoutes('registrations', Registrations::class);
    commonRoutes('series', Series::class);
    commonRoutes('students', Students::class);
    commonRoutes('subjects', Subjects::class);
    commonRoutes('teachers', Teachers::class);
    commonRoutes('users', Users::class);

});

Route::fallback(function () {
    return response()->json(Notify::warning('Destino solicitado não existe...'), 404);
});
