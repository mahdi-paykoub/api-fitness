<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Requestsharecode;
use Illuminate\Http\Request;

class RequestsharecodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $courses = Requestsharecode::with('user')->get();
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در دریافت دوره ها بوجود آمد.']]);
        }

        return response()->json(['status' => true, 'data' => $courses]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Requestsharecode $requestsharecode)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Requestsharecode $requestsharecode)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Requestsharecode $requestsharecode)
    {
        //
    }
}
