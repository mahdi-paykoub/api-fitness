<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\UserInfoStatus;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $orders = Order::where('status' , '!=' , 'unpaid')->with('user')->get();
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در دریافت دوره ها بوجود آمد.']]);
        }

        return response()->json(['status' => true, 'data' => $orders]);
    }

    public function getUserStatusByorderId(Request $request, $id)
    {
        try {
            $userInfoStatus = UserInfoStatus::where('order_id', $id)->first();
            $userSize = $userInfoStatus->userSize()->first();
            $programs = $userInfoStatus->programs()->get();
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در دریافت بوجود آمد.']]);
        }

        return response()->json(['status' => true, 'programs' => $programs, 'userSize' => $userSize]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
