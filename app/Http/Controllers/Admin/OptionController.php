<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OptionController extends Controller
{
    public function adminSmsOption(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'key' => 'required',
            'value' => 'required',
        ]);
        if ($validation->fails())
            return response()->json(['status' => false, 'message' => $validation->errors()->all()]);

        try {
            $user = Option::updateOrCreate(
                ['key' =>  request('key')],
                ['value' => request('value')]
            );
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در دریافت دوره ها بوجود آمد.']]);
        }

        return response()->json(['status' => true]);
    }

    public function addAdminPhoneNumber(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'value1' => 'required',
            'value2' => 'required',
        ]);
        if ($validation->fails())
            return response()->json(['status' => false, 'message' => $validation->errors()->all()]);

        try {
            Option::updateOrCreate(
                ['key' =>  'ADMIN_TICKET_PHONE'],
                ['value' => request('value1')]
            );
            Option::updateOrCreate(
                ['key' =>  'ADMIN_REGISTER_PHONE'],
                ['value' => request('value2')]
            );
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در ثبت بوجود آمد.']]);
        }

        return response()->json(['status' => true]);
    }

    public function getAllOptions()
    {
        try {
            $options = Option::all();
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در دریافت  بوجود آمد.']]);
        }

        return response()->json(['status' => true, 'data' => $options]);
    }
}
