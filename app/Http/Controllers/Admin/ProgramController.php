<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\UserInfoStatus;
use App\Services\SendSms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProgramController extends Controller
{
    public function addProgram(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'title' => 'required',
            'file' => 'required',
            'user_info_status_id' => 'required',
        ]);

        if ($validation->fails())
            return response()->json(['status' => false, 'message' => $validation->errors()->all()]);


        try {
            $file = $validation->valid()['file'];
            $destinationPath = 'assets/files/programs/';
            $file_name = rand(1, 9999) . '-' . $file->getClientOriginalName();
            $file->move(public_path($destinationPath), $file_name);
            $file =  $destinationPath . $file_name;

            $program = Program::create([
                'title' => $validation->valid()['title'],
                'file' => $file,
                'user_info_status_id' => $validation->valid()['user_info_status_id'],
            ]);
            $statusInfo = $program->userInfoStatus()->first();
            $statusInfo->order()->update([
                'status' => 'received_program'
            ]);
            $user = $statusInfo->user()->first();
            //send sms
            $sms = new SendSms();
            $sms->sendProgramNotifToUser($user->phone, $user->name);
        } catch (\Throwable $throwable) {
            // return response()->json(['status' => false, 'message' => [$throwable->getMessage()]]);
            return response()->json(['status' => false, 'message' => ['مشکلی در ارسال برنامه بوجود آمد.']]);
        }

        return response()->json(['status' => true, 'message' => ['برنامه با موفقیت ارسال شد.']]);
    }
}
