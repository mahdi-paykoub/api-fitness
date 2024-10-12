<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {}

    public function getSessionsOfOneCourse(Course $course)
    {
        try {
            $session_of_one_course = $course->sessions()->get();
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در ثبت بوجود آمد.']]);
        }
        return response()->json(['status' => true, 'data' => $session_of_one_course]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'time' => 'required',
            'is_free' => 'required',
            'video' => 'required',
            'course_id' => 'required',
        ]);

        if ($validation->fails())
            return response()->json(['status' => false, 'message' => $validation->errors()->all()]);


        try {
            //upload image
            $file = $validation->valid()['video'];
            $destinationPath = 'assets/video/session/';
            $file_name = rand(1, 9999) . '-' . $file->getClientOriginalName();
            $file->move(public_path($destinationPath), $file_name);
            $data = array_merge($validation->valid(), ["video" => $destinationPath . $file_name]);

            //add db
            Session::create($data);
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در ثبت بوجود آمد.']]);
        }

        return response()->json(['status' => true, 'message' => ['جلسه با موفقیت افزوده شد.']]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Session $session)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Session $session)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Session $session)
    {
        //
    }
}
