<?php

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminFormController;

Route::get('/user', function (Request $request) {

  return UserResource::make($request->user());

})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
  Route::get('/admin/forms', [AdminFormController::class, 'index']);
  Route::post('/admin/forms', [AdminFormController::class, 'store']);
  Route::put('/admin/forms/{id}', [AdminFormController::class, 'update']);
  Route::delete('/admin/forms/{id}', [AdminFormController::class, 'destroy']);
});