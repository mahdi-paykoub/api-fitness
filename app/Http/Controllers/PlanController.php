<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanController extends Controller
{
    public function all()
    {
        try {
            //get all Plan
            $courses = Plan::all();
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در دریافت برنامه ها بوجود آمد.']]);
        }

        return response()->json(['status' => true, 'data' => $courses]);
    }
    public function single(Plan $plan)
    {
        $canBuy = true;
        if (auth('sanctum')->check()) {
            $st = auth('sanctum')->user()->userInfoStatus()->orderBy('id', 'desc')->first();
            if ($st != null) {
                if ((int)count((array)json_decode($st->percentage)) != 4) {
                    $canBuy = false;
                }
            }
        }

        $plan['canBuy'] =  $canBuy;
        return response()->json(['status' => true, 'data' => $plan]);
    }
    public function getPlanById(Request $request, $id)
    {
        try {
            //get course by id
            $plan = Plan::where('id', $id)->first();
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در دریافت  برنامه بوجود آمد.']]);
        }

        return response()->json(['status' => true, 'data' => $plan]);
    }
}
