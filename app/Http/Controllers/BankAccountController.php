<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BankAccountController extends Controller
{
    public function addNewBankAccount(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'sheba' => 'required',
            'cart_number' => 'required',
            'name' => 'required',
        ]);

        if ($validation->fails())
            return response()->json(['status' => false, 'message' => $validation->errors()->all()]);


        try {
            BankAccount::updateOrCreate(
                ['user_id' =>  Auth::user()->id],
                [
                    'name' => $validation->valid()['name'],
                    'sheba' => $validation->valid()['sheba'],
                    'cart_number' => $validation->valid()['cart_number'],
                ]
            );
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => [$throwable->getMessage()]]);
            return response()->json(['status' => false, 'message' => ['مشکلی در ثبت بوجود آمد.']]);
        }

        return response()->json(['status' => true, 'message' => ['اطلاعات حساب با موفقیت ثبت شد.']]);
    }

    public function getUserBankAccount()
    {
        try {
            $bank = Auth::user()->bankAccount()->first();
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در دریافت دوره ها بوجود آمد.']]);
        }

        return response()->json(['status' => true, 'data' => $bank]);
    }
}
