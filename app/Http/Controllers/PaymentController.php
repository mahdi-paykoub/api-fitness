<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Off;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Score;
use App\Models\Settlement;
use App\Models\SubscribeCode;
use App\Services\SendSms;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;
use Shetabit\Payment\Facade\Payment as ShetabitPayment;
use Shetabit\Multipay\Invoice;
use Symfony\Component\VarDumper\VarDumper;

class PaymentController extends Controller
{
    public function payment(Request $request)
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


            $sub_code = SubscribeCode::where('code', $validation->valid()['off_code'])->where('active', 1)->first();
            $off_code = Off::where('code', $validation->valid()['off_code'])->first();
            //off
            if ($off_code != null) {
                if ($off_code->max_usage > $off_code->usage) {
                    if ($off_code->all_user) {
                        $hasOff = $item->offs()->where('off_id', $off_code->id)->exists();
                        if ($hasOff) {
                            // return response()->json($off_code->type);
                            if ($off_code->type == 'percent') {
                                $price =  ($price * ((100 - $off_code->value)  / 100));
                            } elseif ($off_code->type == 'amount') {
                                $price = $price - $off_code->value;
                            }
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
                            }
                        }
                    }
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



            //order
            $order = Auth::user()->orders()->create([
                'orderable_id' => $item->id,
                'orderable_type' => get_class($item),
                'price' => $price,
                'status' => 'unpaid',
                'visit' => $validation->valid()['visit'],
                'subscribe_code' => $validation->valid()['off_code'],
            ]);

            //payment
            $invoice = (new Invoice)->amount($price);
            return ShetabitPayment::callbackUrl(route('payment.callback'))->purchase($invoice, function ($driver, $transactionId) use ($order) {
                $order->payments()->create([
                    'res_number' => $transactionId,
                ]);
            })->pay()->toJson();
        } catch (\Throwable $throwable) {
            // return response()->json(['status' => false, 'message' => ['مشکلی در فرایند پرداخت بوجود آمد.']]);
            return response()->json(['status' => false, 'message' => $throwable->getMessage()]);
        }
    }




    public function payment_callback(Request $request)
    {
        $payment = Payment::where('res_number', $request->input('Authority'))->firstOrFail();

        try {
            $receipt = ShetabitPayment::amount($payment->order()->first()->price)->transactionId($request->input('Authority'))->verify();
            $payment->update([
                'status' => 1
            ]);

            $payment->order()->update([
                'status' => 'paid_uncomplete',
                'turn_code' => Str::random(10) . rand(100, 10000),
            ]);
            $order = $payment->order()->first();
            $user = $order->user()->first();

            $offCode = Off::where('code',  $order->subscribe_code);
            $subCode = SubscribeCode::where('code',  $order->subscribe_code);

            if ($offCode->first() != null) {
                $offCode->increment('usage', 1);
            }
            if ($subCode->first() != null) {
                $subCode->increment('usage', 1);
                $subCode->increment('score', 10);
            }

            if ($order->orderable_type === 'App\Models\Plan') {
                $plan = $order->orderable()->first();
                $percentage = $user->personalInfo()->first() === null ? [] : ['personal'];
                $user->userInfoStatus()->create([
                    'order_id' => $order->id,
                    'percentage' => json_encode($percentage),
                ]);

                $prevStatus = (array)json_decode($user->status);
                if ($user->status != null) {
                    if (!in_array('plan', $prevStatus)) {
                        array_push($prevStatus, 'plan');
                        $user->update([
                            'status' => json_encode($prevStatus)
                        ]);
                    }
                } else {
                    $user->update([
                        'status' => json_encode(['plan'])
                    ]);
                }
                //send sms
                $sms = new SendSms();
                $sms->sendRegisterPlanNotifToUser($user->phone, $user->name, $plan->title, $order->turn_code);
                $sms->sendUserRegisterNotifToAdmin($user->name);
                return Redirect::to('http://localhost:3000/payment/plan/success');
            } elseif ($order->orderable_type === 'App\Models\Course') {
                $prevStatus = (array)json_decode($user->status);
                if ($user->status != null) {
                    if (!in_array('course', $prevStatus)) {
                        array_push($prevStatus, 'course');
                        $user->update([
                            'status' => json_encode($prevStatus)
                        ]);
                    }
                } else {
                    $user->update([
                        'status' => json_encode(['course'])
                    ]);
                }
                return Redirect::to('http://localhost:3000/payment/course/success');
            }
        } catch (InvalidPaymentException $exception) {
            return Redirect::to('http://localhost:3000/payment/fail');
        }
    }
}
