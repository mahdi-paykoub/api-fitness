<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Plan;
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
        ]);

        if ($validation->fails())
            return response()->json(['status' => false, 'message' => $validation->errors()->all()]);



        try {

            //oredr
            if ($validation->valid()['type'] === 'plan') {
                $item = Plan::where('id', $validation->valid()['id'])->first();
            } elseif ($validation->valid()['type'] === 'course') {
                $item = Course::where('id', $validation->valid()['id'])->first();
            }


            $price = $item->price;
            if ($validation->valid()['visit'] == 1 && get_class($item) === 'App\Models\Plan') {
                $price = (int)$item->price + (int) $item->visit_price;
            }


            $order = Auth::user()->orders()->create([
                'orderable_id' => $item->id,
                'orderable_type' => get_class($item),
                'price' => $price,
                'status' => 'unpaid',
                'visit' => $validation->valid()['visit'],
            ]);

            //payment
            $invoice = (new Invoice)->amount(1000);
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
            $receipt = ShetabitPayment::amount(1000)->transactionId($request->input('Authority'))->verify();
            $payment->update([
                'status' => 1
            ]);

            $payment->order()->update([
                'status' => 'paid_uncomplete',
                'turn_code' => Str::random(10) . rand(100, 10000),
            ]);

            $order = $payment->order()->first();
            $user = $order->user()->first();


            if ($order->orderable_type === 'App\Models\Plan') {

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
            }


            return Redirect::to('http://localhost:3000/payment/success');
        } catch (InvalidPaymentException $exception) {
            return Redirect::to('http://localhost:3000/payment/fail');
        }
    }
}
