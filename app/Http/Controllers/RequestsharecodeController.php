<?php

namespace App\Http\Controllers;

use App\Models\Requestsharecode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RequestsharecodeController extends Controller
{
    public function addRequest(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'title' => 'required',
        ]);

        if ($validation->fails())
            return response()->json(['status' => false, 'message' => $validation->errors()->all()]);
        try {
            $reco = Auth::user()->requestsharecode();
            if ($reco->first() == null) {
                $reco->create($validation->valid());
            } else {
                return response()->json(['status' => false, 'message' => ['شما قبلا درخواست خود را برای ادمین فرستاده اید.']]);
            }
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در ثبت بوجود آمد.']]);
        }

        return response()->json(['status' => true, 'message' => ['درخواست با موفقیت ثبت شد.']]);
    }
    public function getUserRequest()
    {
        try {
            $reco = Auth::user()->requestsharecode()->get();
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در دریافت بوجود آمد.']]);
        }

        return response()->json(['status' => true, 'data' => $reco]);
    }
}
