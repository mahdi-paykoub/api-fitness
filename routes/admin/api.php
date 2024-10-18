<?php

use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\ProgramController;
use App\Http\Controllers\Admin\SessionController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::resource('course', CourseController::class);

Route::post('/admin-answer-ticket',  [TicketController::class, 'adminAnswerTicket']);
Route::post('/change-ticket-status',  [TicketController::class, 'changeTicketStatus']);
Route::resource('ticket', TicketController::class);
Route::resource('plan', PlanController::class);

Route::get('/session/{course}',  [SessionController::class, 'getSessionsOfOneCourse']);
Route::resource('session', SessionController::class);

Route::get('/user/ticketable',  [UserController::class, 'getTicketableUsers']);
Route::resource('user', UserController::class);

Route::get('/get-user-info-by-order/{id}',  [OrderController::class, 'getUserStatusByorderId']);
Route::resource('order', OrderController::class);


Route::post('/program',  [ProgramController::class, 'addProgram']);
