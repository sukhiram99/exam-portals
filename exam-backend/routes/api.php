<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiExamFormController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware('auth:api')->group(function () {

    Route::get('/forms', [ApiExamFormController::class, 'index']);
    Route::post('/forms', [ApiExamFormController::class, 'store']);
    Route::get('/forms/{id}', [ApiExamFormController::class, 'show']);
    Route::put('/forms/{id}', [ApiExamFormController::class, 'update']);
    Route::delete('/forms/{id}', [ApiExamFormController::class, 'destroy']);

});

