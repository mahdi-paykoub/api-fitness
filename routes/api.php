<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PersonalInfoController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserImageController;
use App\Http\Controllers\userQuestionsController;
use App\Http\Controllers\UserSizeController;
use App\Models\userQuestions;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware(['auth:sanctum', 'UserPanelAccess'])->group(function () {
    //ticket
    Route::post('/send-ticket', [TicketController::class, 'sendTicket']);
    Route::get('/get-user-tickets', [TicketController::class, 'getUserTickets']);
    Route::get('/get-ticket-chats/{id}', [TicketController::class, 'getTicketChats']);
    Route::post('/answer-ticket', [TicketController::class, 'answerTicket']);
    //user size
    Route::post('/user-size', [UserSizeController::class, 'addSizes']);
    Route::post('/uer-images', [UserImageController::class, 'addImages']);
    Route::post('/user-question', [userQuestionsController::class, 'addQuestions']);

    //order
    Route::get('/get-inquiry-codes', [OrderController::class, 'getInquiryCodes']);
    // tdod
    Route::post('/get-order-turn', [OrderController::class, 'getOrderTurn']);
    //end todo
    Route::get('/get-order-by-user', [OrderController::class, 'getUserPlanOrders']);
    Route::get('/get-order-detail/{id}', [OrderController::class, 'getUserOrderDetail']);
    //personal info
    Route::post('/add-personal-info', [PersonalInfoController::class, 'storePersonalInfo']);
    Route::get('/get-personal-info', [PersonalInfoController::class, 'getUserPersonalInfo']);

    //default size
    Route::get('/get-default-infos', [UserSizeController::class, 'getUserDefaultInfoes']);
});

//course
Route::get('/course/all', [CourseController::class, 'all']);
Route::get('/course/{course}', [CourseController::class, 'single']);
Route::middleware('auth:sanctum')->get('/get-user-courses', [CourseController::class, 'getUserCourses']);
Route::get('/course-id/{id}', [CourseController::class, 'getCourseById']);
//session
Route::middleware('auth:sanctum')->get('/session/{session}', [SessionController::class, 'single']);
Route::middleware('auth:sanctum')->get('/session/{session}/download', [SessionController::class, 'getDownloadSession']);

//plan
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::get('/plan/all', [PlanController::class, 'all']);
Route::get('/plan/{plan}', [PlanController::class, 'single']);
Route::get('/plan-id/{id}', [PlanController::class, 'getPlanById']);

// auth
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify-phone-number', [AuthController::class, 'veryfyPhoneNumber']);
Route::middleware('auth:sanctum')->get('/me', [AuthController::class, 'getMe']);
Route::middleware('auth:sanctum')->get('/get-user-data', [AuthController::class, 'getUserData']);

//payment
Route::middleware('auth:sanctum')->post('/payment', [PaymentController::class, 'payment']);
Route::get('/payment/callback', [PaymentController::class, 'payment_callback'])->name('payment.callback');
