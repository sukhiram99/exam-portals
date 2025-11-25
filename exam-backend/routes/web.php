<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\WebExamFormController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function() {
    Route::get('/forms', [WebExamFormController::class, 'index']);
    Route::get('/forms/create', [WebExamFormController::class, 'create']);
    Route::post('/forms/store', [WebExamFormController::class, 'store']);
});
