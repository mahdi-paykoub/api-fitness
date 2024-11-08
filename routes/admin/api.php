<?php

use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\ExelController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\OptionController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PaymentsController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\ProgramController;
use App\Http\Controllers\Admin\SessionController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;




Route::middleware(['auth:sanctum', 'AdminPanelAccess'])->group(function () {
    Route::resource('course', CourseController::class);
    //ticket
    Route::post('/admin-answer-ticket',  [TicketController::class, 'adminAnswerTicket']);
    Route::post('/change-ticket-status',  [TicketController::class, 'changeTicketStatus']);
    Route::get('/get-ticket-chats/{id}',  [TicketController::class, 'getTicketChats']);
    Route::get('/get-user-other-ticket/{ticketId}/{userId}',  [TicketController::class, 'otherUserTickets']);
    Route::resource('ticket', TicketController::class);

    Route::post('/upload-ck-image',  [PlanController::class, 'uploadCKEditorImages']);
    Route::resource('plan', PlanController::class);

    Route::get('/session/{course}',  [SessionController::class, 'getSessionsOfOneCourse']);
    Route::resource('session', SessionController::class);

    //user
    Route::get('/user/ticketable',  [UserController::class, 'getTicketableUsers']);
    Route::resource('user', UserController::class);

    //order
    Route::get('/get-user-info-by-order/{id}',  [OrderController::class, 'getUserOrderDetail']);
    Route::get('/get-other-orders/{orderId}/{userId}', [OrderController::class, 'getUserOtherOrders']);
    Route::post('/get-orders-by-date', [OrderController::class, 'getOrdersByDate']);
    Route::resource('order', OrderController::class);

    Route::post('/program',  [ProgramController::class, 'addProgram']);
    //payments
    Route::get('/payments',  [PaymentsController::class, 'allPays']);


    //options
    Route::post('/admin-sms-option',  [OptionController::class, 'adminSmsOption']);
    Route::get('/get-all-options',  [OptionController::class, 'getAllOptions']);
    Route::post('/admin-phone-number',  [OptionController::class, 'addAdminPhoneNumber']);
});
