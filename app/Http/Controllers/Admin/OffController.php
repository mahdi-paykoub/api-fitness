<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Off;
use App\Models\Plan;
use App\Models\SubscribeCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $off_codes = Off::get();
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در دریافت دوره ها بوجود آمد.']]);
        }

        return response()->json(['status' => true, 'data' => $off_codes]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'code' => 'required|unique:offs',
            'max_usage' => 'required',
            'type' => 'nullable',
            'value' => 'required',
            'all_user' => 'required',
            'for' => 'required',
            'users' => 'required',
            'products' => 'required',
        ]);


        if ($validation->fails())
            return response()->json(['status' => false, 'message' => $validation->errors()->all()]);
        if (SubscribeCode::where('code', $validation->valid()['code'])->first() != null) {
            return response()->json(['status' => false, 'message' => ['این کد در بین کد های معرف یافت شد. کد شما باید غیر تکراری باشد.']]);
        }

        try {

            DB::beginTransaction();

            $user_ids = array();
            $plan_ids = array();
            $course_ids = array();
            foreach (json_decode($validation->valid()['users'])->artistsArray as $key => $value) {
                $user_ids[] = $value->id;
            }

            $off = Off::create([
                'code' => $validation->valid()['code'],
                'max_usage' => $validation->valid()['max_usage'],
                'type' => $validation->valid()['type'],
                'value' => $validation->valid()['value'],
                'all_user' => (bool) $validation->valid()['all_user'],
                'for' => $validation->valid()['for'],
            ]);
            $off->users()->attach(array_unique($user_ids));


            foreach (json_decode($validation->valid()['products'])->coursesOrPlansListArray as $key => $value) {
                if ($value->id[0]->type == 'course') {
                    Course::find($value->id[0]->id)->offs()->attach($off->id);
                } elseif ($value->id[0]->type == 'plan') {
                    Plan::find($value->id[0]->id)->offs()->attach($off->id);
                }
            }

            DB::commit();
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => [$throwable->getMessage()]]);

            DB::rollBack();
            return response()->json(['status' => false, 'message' => ['مشکلی در ثبت بوجود آمد.']]);
        }

        return response()->json(['status' => true, 'message' => ['کد تخفیف با موفقیت ثبت شد.']]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Off $off)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Off $off)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Off $off)
    {
        try {
            $off->delete();
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در حذف بوجود آمد.']]);
        }
        return response()->json(['status' => true, 'message' => ['کد با موفقیت حذف شد.']]);
    }
}
