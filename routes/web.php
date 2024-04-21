<?php

use App\Http\Controllers\StudentController;
use App\Http\Controllers\TelegramController;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/students', [StudentController::class, 'index']);

Route::post('/telegram/webhook' , [TelegramController::class, 'webhook'])->withoutMiddleware([ValidateCsrfToken::class]);
Route::get('/logs' , [TelegramController::class , 'logs']);
