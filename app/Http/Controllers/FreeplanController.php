<?php

namespace App\Http\Controllers;

use App\Models\Freeplan;
use Illuminate\Http\Request;

class FreeplanController extends Controller
{
    public function freePlans()
    {
        try {
            $freePlans = Freeplan::all();
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در دریافت برنامه ها بوجود آمد.']]);
        }

        return response()->json(['status' => true, 'data' => $freePlans]);
    }
}
