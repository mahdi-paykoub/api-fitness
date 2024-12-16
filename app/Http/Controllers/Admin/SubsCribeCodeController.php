<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\Off;
use App\Models\Settlement;
use App\Models\SubscribeCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubsCribeCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            //get all courses
            $courses = SubscribeCode::with('user')->get();
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در دریافت کد ها بوجود آمد.']]);
        }

        return response()->json(['status' => true, 'data' => $courses]);
    }

    public function changeActive(Request $request, SubscribeCode $subscribe_code)
    {
        $validation = Validator::make($request->all(), [
            'status' => 'required',
        ]);

        if ($validation->fails())
            return response()->json(['status' => false, 'message' => $validation->errors()->all()]);


        try {
            $subscribe_code->update([
                'active' => $validation->valid()['status']
            ]);
        } catch (\Throwable $throwable) {
            // return response()->json(['status' => false, 'message' => [$throwable->getMessage()]]);
            return response()->json(['status' => false, 'message' => ['مشکلی در ثبت بوجود آمد.']]);
        }

        return response()->json(['status' => true, 'message' => ['وضعیت کد تغییر کرد']]);
    }
    public function getUserSubscribeCodeInfo($settlement_id)
    {
        try {
            $settlement = Settlement::where('id', $settlement_id)->first();
            $code =  SubscribeCode::where('user_id', $settlement->user_id)->with('user')->first();
            $bankInfo = BankAccount::where('id', $settlement->user_id)->first();
        } catch (\Throwable $throwable) {
            // return response()->json(['status' => false, 'message' => [$throwable->getMessage()]]);
            return response()->json(['status' => false, 'message' => ['مشکلی در ثبت بوجود آمد.']]);
        }

        return response()->json(['status' => true, 'data' => $code, 'settlement' => $settlement, 'bankInfo' => $bankInfo]);
    }
    public function generateCode()
    {
        try {

            do {
                $code = 'morabi_' .  rand(1000, 10000);
            } while (in_array($code, SubscribeCode::pluck('code')->toArray()));
        } catch (\Throwable $throwable) {
            // return response()->json(['status' => false, 'message' => [$throwable->getMessage()]]);
            return response()->json(['status' => false, 'message' => ['مشکلی در ثبت بوجود آمد.']]);
        }

        return response()->json(['status' => true, 'data' => $code]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'code' => 'required|unique:subscribe_codes',
            'type' => 'required',
            'value' => 'required',
            'for' => 'required',
            'user_id' => 'required',
        ]);

        if ($validation->fails())
            return response()->json(['status' => false, 'message' => $validation->errors()->all()]);
        if (Off::where('code', $validation->valid()['code'])->first() != null) {
            return response()->json(['status' => false, 'message' => ['این کد در بین کد های تخفیف یافت شد. کد شما باید غیر تکراری باشد.']]);
        }

        try {
            $code = SubscribeCode::where('user_id', $validation->valid()['user_id'])->first();
            if ($code == null) {
                $user = User::where('id', $validation->valid()['user_id'])->first();
                SubscribeCode::create($validation->valid());
                $user->requestsharecode()->update([
                    'has_code' => true
                ]);
            } else {
                return response()->json(['status' => false, 'message' => ['قبلا برای این کاربر کد معرف ثبت شده است.']]);
            }
        } catch (\Throwable $throwable) {
            // return response()->json(['status' => false, 'message' => [$throwable->getMessage()]]);
            return response()->json(['status' => false, 'message' => ['مشکلی در ثبت بوجود آمد.']]);
        }

        return response()->json(['status' => true, 'message' => ['کد با موفقیت ثبت شد.']]);
    }

    /**
     * Display the specified resource.
     */
    public function show(SubscribeCode $subscribeCode)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubscribeCode $subscribeCode)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubscribeCode $subscribeCode)
    {
        //
    }
}
