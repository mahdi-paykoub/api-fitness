<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\UserInfoStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function getUserPlanOrders()
    {
        try {
            $orders = Auth::user()->orders()->where('status', '!=', 'unpaid')->where('orderable_type', 'App\Models\Plan')
                ->with('orderable')
                ->get();
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در دریافت سفارشات  بوجود آمد.']]);
        }

        return response()->json(['status' => true, 'data' => $orders]);
    }


    public function getUserOrderDetail(Request $request, $id)
    {
        try {
            $order = Order::where('id', $id)->first();
            if (Auth::user()->id === $order->user_id) {
                $userInfoStatus = $order->userInfoStatus()->first();

                $size = $userInfoStatus->userSize()->first();
                $programs = $userInfoStatus->programs()->get();
                $question = $userInfoStatus->userQuestion()->first();
                $image = $userInfoStatus->userImage()->first();
            } else {
                return response()->json(['status' => false, 'message' => ['مشکلی در احراز حویت وجود دارد.']]);
            }
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در دریافت بوجود آمد.']]);
        }

        return response()->json([
            'status' => true,
            'userSize' => $size,
            'programs' => $programs,
            'questions' => $question,
            'image' => $image,
        ]);
    }


    public function getInquiryCodes()
    {
        try {
            $orders = Auth::user()->orders()->where('status', 'complete')->where('orderable_type', 'App\Models\Plan')
                ->with('orderable')->get();
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در دریافت سفارشات  بوجود آمد.']]);
        }
        return response()->json(['status' => true, 'data' => $orders]);
    }

    public function getOrderTurn(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'code' => 'required',
        ]);

        if ($validation->fails())
            return response()->json(['status' => false, 'message' => $validation->errors()->all()]);

        try {
            $codes = Order::where('status', 'complete')->pluck('turn_code')->toArray();
            $index = array_search($validation->valid()['code'], $codes);
            if ($index === false) {
                return response()->json(['status' => false, 'message' => ['کد وارد شده صحیح نیست.']]);
            }
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => [$throwable->getMessage()]]);
            // return response()->json(['status' => false, 'message' => ['مشکلی در دریافت نوبت شما  بوجود آمد.']]);
        }
        return response()->json(['status' => true, 'data' => $index]);
    }

  
   
}
