<?php

use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\FreeplanController;
use App\Http\Controllers\Admin\OffController;
use App\Http\Controllers\Admin\OptionController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PaymentsController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\Admin\ProgramController;
use App\Http\Controllers\Admin\RequestsharecodeController;
use App\Http\Controllers\Admin\SessionController;
use App\Http\Controllers\Admin\SettlementController;
use App\Http\Controllers\Admin\SssController;
use App\Http\Controllers\Admin\SubsCribeCodeController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\UserController;
use App\Models\Settlement;
use Illuminate\Support\Facades\Route;




Route::middleware(['auth:sanctum', 'AdminPanelAccess'])->group(function () {
    Route::resource('course', CourseController::class);
    //ticket
    Route::post('/admin-answer-ticket',  [TicketController::class, 'adminAnswerTicket']);
    Route::post('/change-ticket-status',  [TicketController::class, 'changeTicketStatus']);
    Route::get('/get-ticket-chats/{id}',  [TicketController::class, 'getTicketChats']);
    Route::get('/get-user-other-ticket/{ticketId}/{userId}',  [TicketController::class, 'otherUserTickets']);
    Route::get('/admin-ticket',  [TicketController::class, 'adminTickets']);
    Route::resource('ticket', TicketController::class);

    Route::get('/get-course-plan',  [PlanController::class, 'getCoursePlan']);
    Route::post('/upload-ck-image',  [PlanController::class, 'uploadCKEditorImages']);
    Route::post('/handle-plan-active/{plan}',  [PlanController::class, 'handlePlanActivation']);
    Route::resource('plan', PlanController::class);
    //free plan
    Route::resource('freeplan', FreeplanController::class);

    Route::get('/session/{course}',  [SessionController::class, 'getSessionsOfOneCourse']);
    Route::resource('session', SessionController::class);

    //user
    Route::post('/user/add-user-prev-info',  [UserController::class, 'storUserPrevInfos']);
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
    Route::post('/score-option',  [OptionController::class, 'userScoreSetting']);
    Route::post('/admin-sms-option',  [OptionController::class, 'adminSmsOption']);
    Route::get('/get-all-options',  [OptionController::class, 'getAllOptions']);
    Route::post('/admin-phone-number',  [OptionController::class, 'addAdminPhoneNumber']);
    //contact us
    Route::get('/zero-update/{sub_id}',  [SettlementController::class, 'updateZeroScore']);
    Route::resource('settlement', SettlementController::class);
    Route::resource('contact-us', RequestsharecodeController::class);
    Route::get('/get-unique-code',  [SubsCribeCodeController::class, 'generateCode']);
    Route::post('/change-active/{subscribe_code}',  [SubsCribeCodeController::class, 'changeActive']);
    Route::get('/get-user-subCode-info/{settlement_id}',  [SubsCribeCodeController::class, 'getUserSubscribeCodeInfo']);
    Route::resource('subscribe-code', SubsCribeCodeController::class);

    //off
    Route::resource('off', OffController::class);
});


// Route::get('routes', function () {
//     $routeCollection = Route::getRoutes();

//     echo "<table style='width:100%'>";
//     echo "<tr>";
//     echo "<td width='10%'><h4>HTTP Method</h4></td>";
//     echo "<td width='10%'><h4>Route</h4></td>";
//     echo "<td width='10%'><h4>Name</h4></td>";
//     echo "<td width='70%'><h4>Corresponding Action</h4></td>";
//     echo "</tr>";
//     foreach ($routeCollection as $value) {
//         echo "<tr>";
//         echo "<td>" . $value->methods()[0] . "</td>";
//         echo "<td>" . $value->uri() . "</td>";
//         echo "<td>" . $value->getName() . "</td>";
//         echo "<td>" . $value->getActionName() . "</td>";
//         echo "</tr>";
//     }
//     echo "</table>";
// });
