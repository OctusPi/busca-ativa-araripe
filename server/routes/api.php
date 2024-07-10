<?php

use App\Http\Controllers\AuthController;
use App\Utils\Notify;
use Illuminate\Support\Facades\Route;


Route::controller(AuthController::class)->group(function () {
    Route::prefix('/auth')->group(function () {
        Route::post('/login', 'login');
        Route::get('/logout/{id}', 'logout');
        Route::get('/active', 'active');
        Route::post('/recover', 'recover');
        Route::post('/renew', 'renew');
    });
})->name('auth');


Route::fallback(function () {
    return Response()->json(Notify::warning('Destino solicitado não existe...'), 404);
});
