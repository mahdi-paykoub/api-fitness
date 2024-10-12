<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\PlanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//course
Route::get('/course/all', [CourseController::class, 'all'])->name('all.courses');
Route::get('/course/{course}', [CourseController::class, 'single'])->name('single.course');
//plan
Route::get('/plan/all', [PlanController::class, 'all'])->name('all.plans');
Route::get('/plan/{plan}', [PlanController::class, 'single'])->name('single.plan');

// auth
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify-phone-number', [AuthController::class, 'veryfyPhoneNumber'])->name('verify.phone.number');
