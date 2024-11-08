<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\UserInfoStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $orders = Order::where('status', '!=', 'unpaid')->with('user')->get();
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در دریافت دوره ها بوجود آمد.']]);
        }

        return response()->json(['status' => true, 'data' => $orders]);
    }

    public function getUserOtherOrders(Request $request, $orderId, $userId)
    {
        try {
            $otherOrders = Order::where('user_id', $userId)
                ->where('status', '!=', 'unpaid')
                ->where('id', '!=', $orderId)
                ->get();
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => [$throwable->getMessage()]]);
            // return response()->json(['status' => false, 'message' => ['مشکلی در دریافت نوبت شما  بوجود آمد.']]);
        }
        return response()->json(['status' => true, 'otherOrders' => $otherOrders]);
    }
    public function getUserOrderDetail(Request $request, $id)
    {
        try {
            $order = Order::where('id', $id)->first();

            $userInfoStatus = $order->userInfoStatus()->first();

            $size = $userInfoStatus->userSize()->first();
            $programs = $userInfoStatus->programs()->get();
            $question = $userInfoStatus->userQuestion()->first();
            $image = $userInfoStatus->userImage()->first();
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

    public function getOrdersByDate(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'day1' => 'required',
            'month1' => 'required',
            'year1' => 'required',
            'day2' => 'required',
            'month2' => 'required',
            'year2' => 'required',

        ]);

        // return response()->json($validation->valid()['features']);
        if ($validation->fails())
            return response()->json(['status' => false, 'message' => $validation->errors()->all()]);

        try {
            $from =  \Morilog\Jalali\CalendarUtils::toGregorian($validation->valid()['year1'],  $validation->valid()['month1'],   $validation->valid()['day1']);
            $from = $from[0] . '/' . $from[1] . '/' . $from[2];
            $to =  \Morilog\Jalali\CalendarUtils::toGregorian($validation->valid()['year2'],  $validation->valid()['month2'],   $validation->valid()['day2']);
            $to = $to[0] . '/' . $to[1] . '/' . $to[2];
            $orders = Order::whereBetween('created_at', [$from, $to])->where('status', '!=', 'unpaid')->with('user')->get();
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در دریافت دوره ها بوجود آمد.']]);
        }

        return response()->json(['status' => true, 'data' => $orders]);
    }
}
