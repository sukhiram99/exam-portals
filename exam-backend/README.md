## 1##

(1) create app laravel use commands : composer create-project laravel/laravel exam-backend
(2) open php my admin create table use sql command databse name exam_portal
(2) install jwt packages
(4) modify the user table for our requirements
(5) create modal and migrations for examform and payments use php artisan make:model Payment -m command for create both table and modal
(6) install composer require jason-guru/laravel-make-repository --dev packages
php artisan install:api

# 📘 Exam Backend – Laravel Project

A backend system built using Laravel, following clean architecture with Repository Pattern, JWT Authentication, REST API + Web Routes, and MySQL database.
This backend is designed for an Exam Form Submission and Payment Management System.

## 🚀 Features

✔ Laravel 10+
✔ JWT Authentication (Single Login Enforcement)
✔ Repository Pattern Architecture
✔ Web + API Controllers
✔ Exam Form Module
✔ Payments Module
✔ MySQL Database
✔ Model–Migration Layer
✔ Clean Folder Structure
✔ Ready for Razorpay / Stripe integration

## 🏗 1. Project Setup

Create Laravel Application
composer create-project laravel/laravel exam-backend

Move into the project folder:

cd exam-backend

## 🗄 2. Database Setup

Create MySQL Database

Open phpMyAdmin (or CLI) and run:

CREATE DATABASE exam_portal;

Update .env file
DB_DATABASE=exam_portal
DB_USERNAME=root
DB_PASSWORD=

## 🔐 3. Install JWT Authentication

Install package:

composer require tymon/jwt-auth

Publish configuration:

php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"

Generate secret key:

php artisan jwt:secret

## 🔧 4. Modify Users Table

Update the users migration to match project requirements:

Add phone number

Add session tracking field

Support single-login functionality

Example fields added:

$table->string('phone')->nullable()->unique();
$table->string('current_session_id')->nullable();

Run migration:

php artisan migrate

## 🧱 5. Create Migrations & Models

Create ExamForm Model & Migration
php artisan make:model ExamForm -m

Create Payment Model & Migration
php artisan make:model Payment -m

Edit migrations and run:

php artisan migrate

## 🧩 6. Install Repository Pattern Package

To follow clean architecture:

composer require jason-guru/laravel-make-repository --dev

Generate repository files:

php artisan make:repository ExamForm
php artisan make:repository Payment

## 🧰 7. Install Laravel API Scaffold (Optional)

php artisan install:api

This adds:

API controller stubs

Token middleware

API route structure

## 📦 8. Project Architecture

app/
├── Models/
├── Http/
│ ├── Controllers/
│ │ ├── Web/
│ │ └── Api/
├── Repositories/
│ ├── Interfaces/
│ └── Eloquent/
database/
├── migrations/
routes/
├── web.php
└── api.php

## 🌐 9. Web Routes (Example)

Route::middleware('auth')->group(function() {
Route::get('/forms', [WebExamFormController::class, 'index']);
Route::get('/forms/create', [WebExamFormController::class, 'create']);
Route::post('/forms/store', [WebExamFormController::class, 'store']);
});

## 📱 10. API Routes (Example)

Route::middleware('auth:api')->group(function () {

    Route::get('/forms', [ApiExamFormController::class, 'index']);
    Route::post('/forms', [ApiExamFormController::class, 'store']);
    Route::get('/forms/{id}', [ApiExamFormController::class, 'show']);
    Route::put('/forms/{id}', [ApiExamFormController::class, 'update']);
    Route::delete('/forms/{id}', [ApiExamFormController::class, 'destroy']);

});

## 🧪 11. Testing

Use Postman, Thunder Client, or Laravel built-in API tester.

Test:

✔ Register
✔ Login (JWT)
✔ Create Exam Form
✔ Update / Delete
✔ Payment routes
✔ Single login enforcement

## 📄 12. Future Enhancements

Razorpay / Stripe payment integration

PDF receipt generator (DOMPDF)

Admin panel

Notification system

Enhanced validation

## 1 Add the role permissin packages : composer require spatie/laravel-permission
