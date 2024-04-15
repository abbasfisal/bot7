<?php

use App\Http\Controllers\StudentController;
use App\Http\Controllers\TelegramController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/students', [StudentController::class, 'index']);

Route::any('/telegram/webhook' , [TelegramController::class, 'webhook']);
