<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Ticket;
use App\Services\SendSms;
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
            'file' => 'nullable',

        ]);

        if ($validation->fails())
            return response()->json(['status' => false, 'message' => $validation->errors()->all()]);


        try {
            //upload file
            $file = null;

            if ($validation->valid()['file'] != 'undefined') {
                $file = $validation->valid()['file'];
                $destinationPath = 'assets/files/tickets/';
                $file_name = rand(1, 9999) . '-' . $file->getClientOriginalName();
                $file->move(public_path($destinationPath), $file_name);
                $file =  $destinationPath . $file_name;
            }
            $ticket = Auth::user()->tickets()->create([
                'title' => $validation->valid()['title'],
                'status' => 'open',
                'admin' => false

            ]);
            $ticket->chats()->create([
                'message' => $validation->valid()['message'],
                'admin' => false,
                'file' => $file,
            ]);

            //send sms
            $sms = new SendSms();
            $sms->sendTicketNotifToAdmin(Auth::user()->name);
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => [$throwable->getMessage()]]);
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
        if (Auth::user()->id === $ticket->user_id) {
            return response()->json(['status' => true, 'data' => $chats, 'ticket' => $ticket]);
        } else {
            return response()->json(['status' => false, 'message' => ['احراز هویت مشکل دارد']]);
        }
    }
    public function answerTicket(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'ticket_id' => 'required',
            'message' => 'required',
            'file' => 'nullable',

        ]);

        if ($validation->fails())
            return response()->json(['status' => false, 'message' => $validation->errors()->all()]);


        try {
            $ticket = Ticket::where('id', $validation->valid()['ticket_id'])->first();
            if (Auth::user()->id === $ticket->user_id) {
                //upload file
                $file = null;

                if ($validation->valid()['file'] != 'undefined') {
                    $file = $validation->valid()['file'];
                    $destinationPath = 'assets/files/tickets/';
                    $file_name = rand(1, 9999) . '-' . $file->getClientOriginalName();
                    $file->move(public_path($destinationPath), $file_name);
                    $file =  $destinationPath . $file_name;
                }

                Chat::create([
                    'ticket_id' => $validation->valid()['ticket_id'],
                    'message' => $validation->valid()['message'],
                    'file' => $file,
                    'admin' => false,
                ]);
                $ticket->touch();
                //send sms
                $sms = new SendSms();
                $sms->sendTicketNotifToAdmin(Auth::user()->name);
            }
        } catch (\Throwable $throwable) {
            return response()->json(['status' => false, 'message' => ['مشکلی در ارسال جواب بوجود آمد.']]);
            // return response()->json(['status' => false, 'message' => $throwable->getMessage()]);
        }

        return response()->json(['status' => true, 'message' =>  ['پیام با موفقیت ارسال شد.']]);
    }
}
