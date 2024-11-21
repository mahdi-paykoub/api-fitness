<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\User;
use App\Services\SendSms;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|min:2|max:30',
            'phone' => 'required|unique:users|regex:/^[0][9][0-9]{9,9}$/',
        ]);

        if ($validation->fails())
            return response()->json(['status' => false, 'message' => $validation->errors()->all()]);


        try {
            $user = User::create($validation->valid());
            // generate code
            $max = pow(10, 6);
            $min = $max / 10 - 1;
            $code = mt_rand($min, $max);

            $user->customTokens()->create(['code' => $code]);

            //send sms
            $sms = new SendSms();
            $sms->sendCode($validation->valid()['phone'], $code);
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در ثبت بوجود آمد.']]);
        }
        return response()->json(['status' => true, 'phone' => $validation->valid()['phone'], 'message' => ['کد 5 رقمی برای شما ارسال شد.']]);
    }

    public function veryfyPhoneNumber(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'code' => 'required|digits:6|numeric',
            'phone' => 'required|regex:/^[0][9][0-9]{9,9}$/',
        ]);

        if ($validation->fails())
            return response()->json(['status' => false, 'message' => $validation->errors()->all()]);
        try {
            $user = User::where('phone', $validation->valid()['phone'])->first();
            if (!$user) {
                return response()->json(['status' => false, 'message' => ['شماره موبایل وارد شده مطابقت ندارد  .']]);
            }
            $token = $user->customTokens()->orderBy('id', 'desc')->first();
            if (!$token) {
                return response()->json(['status' => false, 'message' => ['توکنی برای شما ایجاد نشده است. فرایند ثبت نام را دوباره انجام دهید.']]);
            }
            // validation code
            if ($token->used) {
                return response()->json(['status' => false, 'message' => ['کد وارد شده قبلا استفاده شده است.']]);
            }
            if ($token->created_at->diffInMinutes(Carbon::now()) > 5) {
                return response()->json(['status' => false, 'message' => ['تاریخ انقضای کد تمام شده است.']]);
            }

            if ($token->code !== $validation->valid()['code']) {
                return response()->json(['status' => false, 'message' => ['کد وارد شده صحیح نیست.']]);
            }


            $token->update([
                'used' => true
            ]);
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در تایید بوجود آمد.']]);
        }


        return response()->json([
            'status' => true,
            'message' => ['اطلاعات شما با موفقیت ثبت شد.'],
            'token' => $user->createToken($user->name)->plainTextToken
        ]);
    }
    public function login(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'phone' => 'required|regex:/^[0][9][0-9]{9,9}$/',
        ]);

        if ($validation->fails())
            return response()->json(['status' => false, 'message' => $validation->errors()->all()]);

        try {
            $user = User::where('phone', $validation->valid()['phone'])->first();
            if (!$user) {
                return response()->json(['status' => false, 'message' => ['شماره موبایل وارد شده پیدا نشد.']]);
            }

            // generate code
            $max = pow(10, 6);
            $min = $max / 10 - 1;
            $code = mt_rand($min, $max);

            $user->customTokens()->create(['code' => $code]);

            //send sms
            $sms = new SendSms();
            $sms->sendCode($user->phone, $code);
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در ورود بوجود آمد. لطفا دوباره سعی نمایید.']]);
        }

        return response()->json([
            'status' => true,
            'phone' => $validation->valid()['phone'],
            'message' => ['کد 5 رقمی برای شما ارسال شد.']
        ]);
    }
    public function getMe()
    {
        if (auth('sanctum')->check()) {
            return response()->json(['status' => true, 'data' => auth()->user()]);
        }
        return response()->json(['status' => false]);
    }

    public function getUserData()
    {
        try {
            $courseCount = Order::where('user_id', Auth::user()->id)
                ->where('status', '!=', 'unpaid')
                ->where('orderable_type', 'App\Models\Corse')
                ->get()->count();
            $planCount = Order::where('user_id', Auth::user()->id)
                ->where('status', '!=', 'unpaid')
                ->where('orderable_type', 'App\Models\Plan')
                ->get()->count();
            $ticketCount = Ticket::where('user_id', Auth::user()->id)
                ->get()->count();
            $lastPercentage = json_decode(Auth::user()->userInfoStatus()->orderBy('id', 'desc')->first()->percentage);
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در دریافت بوجود آمد.']]);
        }
        return response()->json([
            'status' => true,
            'courseCount' => $courseCount,
            'planCount' => $planCount,
            'ticketCount' => $ticketCount,
            'lastPercentage' => $lastPercentage,

        ]);
    }

    public function logout()
    {

        try {
            Auth::user()->currentAccessToken()->delete();
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در خروج بوجود آمد.']]);
        }
        return response()->json(['status' => true, 'message' => ['شما با موفقیت خارج شدید']]);
    }
}
