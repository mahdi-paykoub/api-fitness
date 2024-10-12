<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\plan;
use App\Models\Plan as ModelsPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            //get all courses
            $plans = ModelsPlan::all();
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در دریافت برنامه ها بوجود آمد.']]);
        }

        return response()->json(['status' => true, 'data' => $plans]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'title' => 'required',
            'slug' => 'required',
            'price' => 'required',
            'description' => 'required',
            'body' => 'required',
        ]);

        if ($validation->fails())
            return response()->json(['status' => false, 'message' => $validation->errors()->all()]);

        try {
            //add db
            plan::create($validation->valid());
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در ثبت بوجود آمد.']]);
        }

        return response()->json(['status' => true, 'message' => ['برنامه با موفقیت افزوده شد.']]);
    }

    /**
     * Display the specified resource.
     */
    public function show(plan $plan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, plan $plan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(plan $plan)
    {
        try {
            $plan->delete();
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در حذف بوجود آمد.']]);
        }
        return response()->json(['status' => true, 'message' => ['دوره با موفقیت حذف شد.']]);
    }
}
