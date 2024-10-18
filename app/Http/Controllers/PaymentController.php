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
use Shetabit\Multipay\Exceptions\InvalidPaymentException;
use Shetabit\Payment\Facade\Payment as ShetabitPayment;
use Shetabit\Multipay\Invoice;

class PaymentController extends Controller
{
    public function payment(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id' => 'required',
            'type' => 'required',
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

            $order = Auth::user()->orders()->create([
                'orderable_id' => $item->id,
                'orderable_type' => get_class($item),
                'price' => $item->price,
                'status' => 'unpaid',
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
                'status' => 'paid_uncomplete'
            ]);

            $order = $payment->order()->first();
            $user = $order->user()->first();

            $user->update([
                'active' => true
            ]);

            $user->userInfoStatus()->create([
                'order_id' => $order->id,
                'is_complete_info' => false,
                'percentage' => '0',
            ]);
            return Redirect::to('http://localhost:3000/payment/success');
        } catch (InvalidPaymentException $exception) {
            return Redirect::to('http://localhost:3000/payment/fail');
        }
    }
}
