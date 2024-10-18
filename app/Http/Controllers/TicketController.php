<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class TicketController extends Controller
{
    public function sendTicket(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'title' => 'required',
            'message' => 'required',

        ]);

        if ($validation->fails())
            return response()->json(['status' => false, 'message' => $validation->errors()->all()]);


        try {
            $ticket = Auth::user()->tickets()->create([
                'title' => $validation->valid()['title'],
                'status' => 'open',
                'admin' => false

            ]);
            $ticket->chats()->create([
                'message' => $validation->valid()['message'],
                'admin' => false,
                'file' => 'sss',
            ]);
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در ارسال تیکت بوجود آمد.']]);
        }

        return response()->json(['status' => true, 'message' => ['تیکت با موفقیت ارسال شد.']]);
    }

    public function getUserTickets()
    {
        $tickets = Auth::user()->tickets()->get();
        return response()->json(['status' => true, 'data' => $tickets]);
    }

    public function getTicketChats(Request $request, $id)
    {
        $ticket =  Ticket::where('id', $id)->first();
        $chats = $ticket->chats()->get();
        return response()->json(['status' => true, 'data' => $chats, 'ticket' => $ticket]);
    }
    public function answerTicket(Request $request)
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
                'admin' => false,
            ]);
            Ticket::where('id', $validation->valid()['ticket_id'])->first()->touch();
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در ارسال جواب بوجود آمد.']]);
            // return response()->json(['status' => false, 'message' => $throwable->getMessage()]);
        }

        return response()->json(['status' => true, 'message' =>  ['پیام با موفقیت ارسال شد.']]);
    }
}
