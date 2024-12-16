<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Off;
use App\Models\Plan;
use App\Models\SubscribeCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OffController extends Controller
{
    public function validationOff(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id' => 'required',
            'type' => 'required',
            'visit' => 'nullable',
            'off_code' => 'nullable',
        ]);

        if ($validation->fails())
            return response()->json(['status' => false, 'message' => $validation->errors()->all()]);



        try {
            //set plan or course
            if ($validation->valid()['type'] === 'plan') {
                $item = Plan::where('id', $validation->valid()['id'])->first();
            } elseif ($validation->valid()['type'] === 'course') {
                $item = Course::where('id', $validation->valid()['id'])->first();
            }

            //price
            $price = $item->price;
            if ($item->off_price != null) {
                $price = $item->off_price;
            }

            //off & sub
            $sub_code = SubscribeCode::where('code', $validation->valid()['off_code'])->where('active', 1)->first();
            $off_code = Off::where('code', $validation->valid()['off_code'])->first();
            if ($off_code == null && $sub_code == null) {
                return response()->json(['status' => false, 'message' => ['کد معتبر نیست']]);
            }
            //off
            if ($off_code != null) {
                if ($off_code->max_usage > $off_code->usage) {
                    if ($off_code->all_user) {
                        $hasOff = $item->offs()->where('off_id', $off_code->id)->exists();
                        if ($hasOff) {
                            if ($off_code->type == 'percent') {
                                $price =  ($price * ((100 - $off_code->value)  / 100));
                            } elseif ($off_code->type == 'amount') {
                                $price = $price - $off_code->value;
                            }
                        } else {
                            return response()->json(['status' => false, 'message' => ['کد معتبر نیست']]);
                        }
                    } else {
                        $hasUserOff = Auth::user()->offs()->where('off_id', $off_code->id)->exists();
                        if ($hasUserOff) {
                            $hasOff = $item->offs()->where('off_id', $off_code->id)->exists();
                            if ($hasOff) {
                                if ($off_code->type == 'percent') {
                                    $price =  ($price * ((100 - $off_code->value)  / 100));
                                } elseif ($off_code->type == 'amount') {
                                    $price = $price - $off_code->value;
                                }
                            } else {
                                return response()->json(['status' => false, 'message' => ['کد معتبر نیست']]);
                            }
                        } else {
                            return response()->json(['status' => false, 'message' => ['کد معتبر نیست']]);
                        }
                    }
                } else {
                    return response()->json(['status' => false, 'message' => ['کد معتبر نیست']]);
                }
            }
            //sub
            if ($sub_code != null) {
                if (substr(strtolower(get_class($item)), 11) ==  $sub_code->for || $sub_code->for == 'all') {
                    if ($sub_code->type == 'percent') {
                        $price =  ($price * ((100 - $sub_code->value)  / 100));
                    } elseif ($sub_code->type == 'amount') {
                        $price = $price - $sub_code->value;
                    }
                }
            }


            //visit
            if ($validation->valid()['visit'] == 1 && get_class($item) === 'App\Models\Plan') {
                $price = (int)$price + (int) $item->visit_price;
            }
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در فرایند پرداخت بوجود آمد.']]);
            // return response()->json(['status' => false, 'message' => $throwable->getMessage()]);
        }
        return response()->json(['status' => true, 'price' => $price]);
    }
}
