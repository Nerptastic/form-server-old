<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormController;
use Illuminate\Http\Request;

Route::post('/form/submit', [FormController::class, 'store'])
     ->middleware('throttle:5,1'); // 5 requests per minute

Route::get('/', function () {
    return view('welcome');
});