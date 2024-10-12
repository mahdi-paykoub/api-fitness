<?php

use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\SessionController;
use Illuminate\Support\Facades\Route;

Route::resource('course', CourseController::class);
Route::resource('plan', PlanController::class);
Route::get('/session/{course}',  [SessionController::class, 'getSessionsOfOneCourse']);
Route::resource('session', SessionController::class);
