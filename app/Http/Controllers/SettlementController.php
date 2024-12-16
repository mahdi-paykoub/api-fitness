<?php

namespace App\Http\Controllers;

use App\Models\Option;
use App\Models\Settlement;
use App\Models\SubscribeCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SettlementController extends Controller
{
    public function addSettlement(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'type' => 'required',
        ]);

        if ($validation->fails())
            return response()->json(['status' => false, 'message' => $validation->errors()->all()]);

        // global score
        // if (Auth::user()->subscribeCode()->first()->score >= 100) {
        // code
        // } else {
        //     return response()->json(['status' => false, 'message' => ['شما حداقل امتیاز لازم برای تسویه را ندارید.']]);
        // }

        try {
            if (Settlement::where('user_id', Auth::user()->id)->where('status', 0)->first() == null) {
                $max_score = Option::where('key', 'MAX_SCORE_FOR_SETTLEMENT')->first()->value;
                if ($max_score != null) {
                    if (Auth::user()->subscribeCode()->first() != null) {
                        if ((int)Auth::user()->subscribeCode()->first()->score >= (int)$max_score) {
                            if ($validation->valid()['type'] == 'cash') {
                                if (Auth::user()->bankAccount()->first() != null) {

                                    Auth::user()->settlements()->create([
                                        'type' => $validation->valid()['type']
                                    ]);
                                } else {
                                    return response()->json(['status' => false, 'message' => ['برای تسویه حساب به صورت نقدی ابتدا باید اطلاعات حساب بانکی خود را تکمیل نمایید.']]);
                                }
                            } else {
                                Auth::user()->settlements()->create([
                                    'type' => $validation->valid()['type']
                                ]);
                            }
                        } else {
                            return response()->json(['status' => false, 'message' => ['شما حداقل امتیاز لازم برای تسویه را ندارید.']]);
                        }
                    }else {
                        return response()->json(['status' => false, 'message' => ['هنوز کد معرفی برای شما ثبت نشده است.']]);
                    }
                } else {
                    return response()->json(['status' => false, 'message' => ['حداقل امتیاز برای ارسال درخواست تسویه ثبت نشده است.']]);
                }
            } else {
                return response()->json(['status' => false, 'message' => ['درخواست شما قبلا ارسال شده است.']]);
            }
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => [$throwable->getMessage()]]);
            return response()->json(['status' => false, 'message' => ['مشکلی در ثبت بوجود آمد.']]);
        }

        return response()->json(['status' => true, 'message' => ['درخواست با موفقیت فرستاده شد.']]);
    }

    public function getUserSettlements()
    {
        try {
            //get all courses
            $courses = Auth::user()->settlements()->get();
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در دریافت دوره ها بوجود آمد.']]);
        }

        return response()->json(['status' => true, 'data' => $courses]);
    }
}
