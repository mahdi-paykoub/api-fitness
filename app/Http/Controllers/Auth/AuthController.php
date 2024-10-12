<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Token;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required|unique:users',
        ]);

        if ($validation->fails())
            return response()->json(['status' => false, 'message' => $validation->errors()->all()]);

        try {
            //creat user
            $user = User::create($validation->valid());
            //create code
            $token = Token::create([
                'user_id' => $user->id
            ]);
            if (!$token->sendCode()) {
                throw new Exception("Error");
            }
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در ثبت بوجود آمد.']]);
        }

        return response()->json(['status' => true, 'message' => ['کد 5 رقمی برای شما ارسال شد.'],  'phone' => $validation->valid()['phone']]);
    }

    public function veryfyPhoneNumber(Request $request)
    {

        $validation = Validator::make($request->all(), [
            'code' => 'required',
            'phone' => 'required',
        ]);

        if ($validation->fails())
            return response()->json(['status' => false, 'message' => $validation->errors()->all()]);


        try {
            $user = User::where('phone', $validation->valid()['phone'])->first();
            $token = $user->tokens()->orderBy('id', 'DESC')->first();
            $code =  $token->code;
            if ($validation->valid()['code'] !== $code) {
                return response()->json(['status' => false, 'message' => ['کد وارد شده صحیح نیست.']]);
            }
            if (!$token->isValid()) {
                return response()->json(['status' => false, 'message' => ['کد وارد شده نامعتبر است.']]);
            }
            Token::where('id', $token->id)->update([
                'used' => true
            ]);
            return response()->json(['status' => true, 'message' => ['شماره موبایل شما تایید شد.'], 'token' => $user->createToken('token-name', ['server:update'])->plainTextToken]);
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی  بوجود آمده است.']]);
        }


        // return response()->json(['status' => true, 'message' => ['کد 5 رقمی برای شما ارسال شد.'],  'data' => $code]);
    }
}
