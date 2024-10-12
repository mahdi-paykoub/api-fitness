<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

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
        return response()->json(['status' => true, 'data' => $plan]);
    }
}
