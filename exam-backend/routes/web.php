<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ExamFormController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Auth\LoginController;

use Illuminate\Support\Facades\Auth;
Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('auth.login');
})->name('login');

Route::view('/', 'auth.login');

Route::post('login-submit', [LoginController::class, 'loginSubmit'])->name('loginSubmit');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');


Route::middleware('auth')->group(function() {

   Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
     
        Route::get('dashboard', function () {
            return view('dashboard');
        })->name('dashboard');

        Route::resource('roles', RoleController::class);
        Route::resource('users', UserController::class);
        Route::resource('permissions',PermissionController::class); // ← Add this line

         Route::get('exam/forms', [ExamFormController::class, 'index'])->name('exam.forms');
        Route::get('exam/create', [ExamFormController::class, 'create'])->name('exam.create');
        Route::post('exam/store', [ExamFormController::class, 'store'])->name('exam.store');
        Route::get('exam/{id}/pay', [ExamFormController::class, 'pay'])->name('exam.pay');
        // API Routes for Razorpay
        Route::post('api/create-order', [ExamFormController::class, 'createOrder']);
        Route::post('api/verify-payment', [ExamFormController::class, 'verifyPayment']);

        Route::get('receipt/{payment}', [ExamFormController::class, 'receipt'])->name('receipt.show');
        Route::get('receipt/download/{payment}', [ExamFormController::class, 'downloadReceipt'])->name('receipt.download');
    });

});
