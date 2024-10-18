<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            //get all tickets
            $tickets = Ticket::orderBy('updated_at', 'desc')->get();
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در دریافت تیکت ها بوجود آمد.']]);
        }

        return response()->json(['status' => true, 'data' => $tickets]);
    }


    public function changeTicketStatus(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'status' => 'required',
            'ticket_id' => 'required',
        ]);

        if ($validation->fails())
            return response()->json(['status' => false, 'message' => $validation->errors()->all()]);


        try {
            Ticket::where('id', $validation->valid()['ticket_id'])->first()->update(
                [
                    'status' => $validation->valid()['status'],
                    'timestamps' => false
                ]
            );;
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در ارسال بوجود آمد.']]);
            // return response()->json(['status' => false, 'message' => $throwable->getMessage()]);
        }

        return response()->json(['status' => true, 'message' =>  ['وضعیت تیکت نغییر یافت.']]);
    }

    public function adminAnswerTicket(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'ticket_id' => 'required',
            'message' => 'required',

        ]);

        if ($validation->fails())
            return response()->json(['status' => false, 'message' => $validation->errors()->all()]);


        try {
            Chat::create([
                'ticket_id' => $validation->valid()['ticket_id'],
                'message' => $validation->valid()['message'],
                'file' => 'ggg',
                'admin' => true,
            ]);
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در ارسال جواب بوجود آمد.']]);
            // return response()->json(['status' => false, 'message' => $throwable->getMessage()]);
        }

        return response()->json(['status' => true, 'message' =>  ['پیام با موفقیت ارسال شد.']]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'title' => 'required',
            'message' => 'required',
            'user_id' => 'required',

        ]);

        if ($validation->fails())
            return response()->json(['status' => false, 'message' => $validation->errors()->all()]);


        try {
            $ticket = Ticket::create(
                [
                    'title' => $validation->valid()['title'],
                    'status' => 'open',
                    'admin' => true,
                    'user_id' => $validation->valid()['user_id']
                ]
            );

            $ticket->chats()->create([
                'message' => $validation->valid()['message'],
                'admin' => true,
                'file' => 'sss',
            ]);
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در ارسال تیکت بوجود آمد.']]);
        }

        return response()->json(['status' => true, 'message' => ['تیکت با موفقیت ارسال شد.']]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        //
    }
}
