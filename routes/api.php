<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserSizeController;
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
Route::get('/course/all', [CourseController::class, 'all']);
Route::get('/course/{course}', [CourseController::class, 'single']);
Route::get('/course-id/{id}', [CourseController::class, 'getCourseById']);

//plan
Route::get('/plan/all', [PlanController::class, 'all']);
Route::get('/plan/{plan}', [PlanController::class, 'single']);
Route::get('/plan-id/{id}', [PlanController::class, 'getPlanById']);

// auth
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify-phone-number', [AuthController::class, 'veryfyPhoneNumber']);
Route::middleware('auth:sanctum')->get('/me', [AuthController::class, 'getMe']);
//payment
Route::middleware('auth:sanctum')->post('/payment', [PaymentController::class, 'payment']);
Route::get('/payment/callback', [PaymentController::class, 'payment_callback'])->name('payment.callback');
//ticket
Route::middleware('auth:sanctum')->post('/send-ticket', [TicketController::class, 'sendTicket']);
Route::middleware('auth:sanctum')->get('/get-user-tickets', [TicketController::class, 'getUserTickets']);
Route::get('/get-ticket-chats/{id}', [TicketController::class, 'getTicketChats']);
Route::post('/answer-ticket', [TicketController::class, 'answerTicket']);
//user size
Route::middleware('auth:sanctum')->post('/user-size', [UserSizeController::class, 'addSizes']);

