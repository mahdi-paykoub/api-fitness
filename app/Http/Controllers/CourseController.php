<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $canBuy = true;
        if (auth('sanctum')->check()) {
            $st = auth('sanctum')->user()->orders()
                ->where('orderable_type', 'App\Models\Course')
                ->where('orderable_id', $course->id)->first();
            if ($st != null) {
                $canBuy = false;
            }
        }


        $has_order = null;
        if (auth('sanctum')->check()) {
            $has_order =  auth('sanctum')->user()->orders()
                ->where('status', 'paid_uncomplete')
                ->where('orderable_id', $course->id)
                ->where('orderable_type', get_class($course))
                ->first();
        }


        $data['info'] = [
            'total_time' => 0,
            'session_count' => $sessoins->count(),
            'isUserRegisteredToThisCourse' => $has_order
        ];

        $data['canBuy'] = $canBuy;


        $data['sessions'] = $sessoins->get();
        return response()->json(['status' => true, 'data' => $data]);
    }

    public function getCourseById(Request $request, $id)
    {
        try {
            //get course by id
            $course = Course::where('id', $id)->first();
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در دریافت  دوره بوجود آمد.']]);
        }

        return response()->json(['status' => true, 'data' => $course]);
    }


    public function getUserCourses(Request $request)
    {
        try {
            $courses =  Auth::user()->orders()
                ->where('orderable_type', 'App\Models\Course')
                ->where('status', 'paid_uncomplete')
                ->with('orderable')->get();
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در دریافت دوره ها بوجود آمد.']]);
        }

        return response()->json(['status' => true, 'data' => $courses]);
    }
}
