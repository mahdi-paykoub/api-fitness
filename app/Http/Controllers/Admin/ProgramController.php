<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProgramController extends Controller
{
    public function addProgram(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'title' => 'required',
            // 'file' => 'required',
            'user_info_status_id' => 'required',
        ]);

        if ($validation->fails())
            return response()->json(['status' => false, 'message' => $validation->errors()->all()]);


        try {
            $program = Program::create([
                'title' => $validation->valid()['title'],
                'file' => 'test file',
                'user_info_status_id' => $validation->valid()['user_info_status_id'],
            ]);
            $program->userInfoStatus()->first()->order()->update([
                'status' => 'received_program'
            ]);
        } catch (\Throwable $throwable) {
            // return response()->json(['status' => false, 'message' => [$throwable->getMessage()]]);
            return response()->json(['status' => false, 'message' => ['مشکلی در ارسال برنامه بوجود آمد.']]);
        }

        return response()->json(['status' => true, 'message' => ['برنامه با موفقیت ارسال شد.']]);
    }
}
