<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            //get all courses
            $courses = Course::all();
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در دریافت دوره ها بوجود آمد.']]);
        }

        return response()->json(['status' => true, 'data' => $courses]);
    }
   

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'title' => 'required',
            'price' => 'required',
            'off_price' => 'nullable',
            'image' => 'required',
            'body' => 'required',
            'slug' => 'required',
        ]);

        if ($validation->fails())
            return response()->json(['status' => false, 'message' => $validation->errors()->all()]);


        try {
            //upload image
            $file = $validation->valid()['image'];
            $destinationPath = 'assets/images/course/';
            $file_name = rand(1, 9999) . '-' . $file->getClientOriginalName();
            $file->move(public_path($destinationPath), $file_name);
            $data = array_merge($validation->valid(), ["image" => $destinationPath . $file_name]);

            //add db
            Course::create($data);
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در ثبت بوجود آمد.']]);
        }

        return response()->json(['status' => true, 'message' => ['دوره با موفقیت افزوده شد.']]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        return response()->json(['status' => true, 'data' => $course]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        $validation = Validator::make($request->all(), [
            'title' => 'required',
            'price' => 'required',
            'off_price' => 'nullable',
            'image' => 'required',
            'body' => 'required',
            'slug' => 'required',
        ]);

        if ($validation->fails())
            return response()->json(['status' => false, 'message' => $validation->errors()->all()]);

        try {
            //upload image
            $file = $validation->valid()['image'];
            $destinationPath = 'assets/images/course/';
            $file_name = rand(1, 9999) . '-' . $file->getClientOriginalName();
            $file->move(public_path($destinationPath), $file_name);
            $data = array_merge($validation->valid(), ["image" => $destinationPath . $file_name]);


            $course->update($data);
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در آپدیت بوجود آمد.']]);
        }

        return response()->json(['status' => true, 'data' => $course, 'message' => ['دوره با موفقیت آپدیت شد.']]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        try {
            $course->delete();
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در حذف بوجود آمد.']]);
        }
        return response()->json(['status' => true, 'message' => ['دوره با موفقیت حذف شد.']]);
    }
}
