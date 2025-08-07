<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BpgController as ApiBpgController;
use App\Http\Controllers\Api\TtpbController as ApiTtpbController;

Route::get('/bpg', [ApiBpgController::class, 'index']);
Route::get('/bpg/{bpg}', [ApiBpgController::class, 'show']);
Route::post('/bpg', [ApiBpgController::class, 'store']);
Route::delete('/bpg/{bpg}', [ApiBpgController::class, 'destroy']);

Route::get('/ttpb', [ApiTtpbController::class, 'index']);
Route::get('/ttpb/{ttpb}', [ApiTtpbController::class, 'show']);
Route::post('/ttpb', [ApiTtpbController::class, 'store']);
Route::delete('/ttpb/{ttpb}', [ApiTtpbController::class, 'destroy']);
