<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function all()
    {
        try {
            //get all courses
            $courses = Course::all();
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در دریافت دوره ها بوجود آمد.']]);
        }

        return response()->json(['status' => true, 'data' => $courses]);
    }

    public function single(Course $course)
    {
        $data = $course;
        $sessoins = $course->sessions();
        $data['info'] = [
            'total_time' => 0,
            'session_count' => $sessoins->count(),
        ];
        $data['sessions'] = $sessoins->get();
        return response()->json(['status' => true, 'data' => $data]);
    }
}
