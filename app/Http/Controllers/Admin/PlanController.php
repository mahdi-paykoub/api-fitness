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
            $plans = Plan::all();
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
            'off_price' => 'nullable',
            'description' => 'required',
            'body' => 'required',
            'visit' => 'required',
            'visit_price' => 'nullable',
            'features' => 'nullable',
            'duration' => 'required',
        ]);
        if ($validation->fails())
            return response()->json(['status' => false, 'message' => $validation->errors()->all()]);

        try {
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
        return response()->json(['status' => true, 'data' => $plan]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, plan $plan)
    {
        $validation = Validator::make($request->all(), [
            'title' => 'required',
            'slug' => 'required',
            'price' => 'required',
            'off_price' => 'nullable',
            'description' => 'required',
            'body' => 'required',
            'duration' => 'required',
        ]);
        if ($validation->fails())
            return response()->json(['status' => false, 'message' => $validation->errors()->all()]);

        try {
            $plan->update([
                'title'=> $validation->valid()['title'],
                'slug'=> $validation->valid()['slug'],
                'price'=> $validation->valid()['price'],
                'off_price'=> $validation->valid()['off_price'],
                'description'=> $validation->valid()['description'],
                'body'=> $validation->valid()['body'],
                'duration'=> $validation->valid()['duration'],
            ]);
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => $throwable->getMessage()]);
            return response()->json(['status' => false, 'message' => ['مشکلی در آپدیت بوجود آمد.']]);
        }

        return response()->json(['status' => true, 'data' => $plan, 'message' => ['برنامه با موفقیت آپدیت شد.']]);
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

    public function uploadCKEditorImages(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'files' => 'required',

        ]);
        if ($validation->fails())
            return response()->json(['status' => false, 'message' => $validation->errors()->all()]);


        //upload image
        $file = $validation->valid()['files'];
        $destinationPath = 'assets/images/ckEditor/';
        $file_name = rand(1, 9999) . '-' . $file->getClientOriginalName();
        $file->move(public_path($destinationPath), $file_name);



        return response()->json(['status' => true, 'url' => $destinationPath . $file_name]);
    }
}
