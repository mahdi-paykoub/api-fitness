<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    public function allPays()
    {
        try {
            $pays = Payment::where('status', 1)->with('order', 'order.user', 'order.orderable')->get();
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در دریافت دوره ها بوجود آمد.']]);
        }

        return response()->json(['status' => true, 'data' => $pays]);
    }
}
