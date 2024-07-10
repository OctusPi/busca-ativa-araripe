<?php

use App\Utils\Notify;
use Illuminate\Support\Facades\Route;

Route::fallback(function () {
    return Response()->json(Notify::error('Acesso por caminho inválido'), 403);
});