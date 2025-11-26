<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ExamApiController;
use App\Http\Controllers\API\AuthApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::group(['middleware' => 'auth:api','prefix' => 'user'], function ($router) {

    Route::post('exam-form-submit', [ExamApiController::class, 'submitForm']);
    Route::post('exam-payment/create-order', [ExamApiController::class, 'createRazorpayOrder']);
    Route::post('exam-payment/verify', [ExamApiController::class, 'verifyPayment']);

});

Route::group(['middleware' => 'api','prefix' => 'auth'], function ($router) {
    Route::post('register', [AuthApiController::class, 'register']);
    Route::post('login', [AuthApiController::class, 'login']);
});

Route::group(['middleware' => 'auth:api','prefix' => 'user'], function ($router) {
    Route::post('logout', [AuthApiController::class, 'logout']);
    Route::post('refresh', [AuthApiController::class, 'refresh']);
    Route::post('profile', [AuthApiController::class, 'profile']);
});

