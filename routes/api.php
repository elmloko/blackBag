<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;


Route::get('/apertura', [ApiController::class, 'apertura']);
Route::get('/cerrado', [ApiController::class, 'cerrado']);
Route::get('/expedicion', [ApiController::class, 'expedicion']);
Route::get('/observado', [ApiController::class, 'observado']);
Route::get('/admitido', [ApiController::class, 'admitido']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
