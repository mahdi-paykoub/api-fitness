<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Freeplan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FreeplanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $freePlans = Freeplan::all();
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در دریافت برنامه ها بوجود آمد.']]);
        }

        return response()->json(['status' => true, 'data' => $freePlans]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'title' => 'required',
            'file' => 'required',
        ]);

        if ($validation->fails())
            return response()->json(['status' => false, 'message' => $validation->errors()->all()]);


        try {
            //upload file
            $file = $validation->valid()['file'];
            $destinationPath = 'assets/files/free_plan/';
            $file_name = rand(1, 9999) . '-' . $file->getClientOriginalName();
            $file->move(public_path($destinationPath), $file_name);
            $data = array_merge($validation->valid(), ["file" => $destinationPath . $file_name]);

            //add db
            FreePlan::create($data);
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در ثبت بوجود آمد.']]);
        }

        return response()->json(['status' => true, 'message' => ['برنامه با موفقیت افزوده شد.']]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Freeplan $freeplan)
    {
        try {
            $freeplan->delete();
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در حذف بوجود آمد.']]);
        }
        return response()->json(['status' => true, 'message' => ['برنامه با موفقیت حذف شد.']]);
    }
}
