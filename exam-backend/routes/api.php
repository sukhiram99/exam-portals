<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiExamFormController;
use App\Http\Controllers\API\AuthApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::group(['middleware' => 'api', 'prefix' => 'auth'], function ($router) {
    Route::get('forms', [ApiExamFormController::class, 'index']);
    Route::post('forms', [ApiExamFormController::class, 'store']);
    Route::get('forms/{id}', [ApiExamFormController::class, 'show']);
    Route::put('forms/{id}', [ApiExamFormController::class, 'update']);
    Route::delete('forms/{id}', [ApiExamFormController::class, 'destroy']);

});

Route::group(['middleware' => 'api','prefix' => 'auth'], function ($router) {
    Route::post('register', [AuthApiController::class, 'register']);
    Route::post('login', [AuthApiController::class, 'login']);
    Route::post('logout', [AuthApiController::class, 'logout'])->middleware('auth:api');
    Route::post('refresh', [AuthApiController::class, 'refresh'])->middleware('auth:api');
    Route::post('profile', [AuthApiController::class, 'profile'])->middleware('auth:api');
});

