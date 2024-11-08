<?php

namespace App\Http\Controllers;

use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class SessionController extends Controller
{
    public function single(Session $session)
    {
        $has_order =  Auth::user()->orders()
            ->where('status', 'paid_uncomplete')
            ->where('orderable_id', $session->course()->first()->id)
            ->where('orderable_type', get_class($session->course()->first()))
            ->first();

        if ($has_order != null) {
            return response()->json(['status' => true, 'data' => $session, 'course' => $session->course()->with('sessions')->first()]);
        }
    }

    public function getDownloadSession(Session $session)
    {
        $has_order =  Auth::user()->orders()
            ->where('status', 'paid_uncomplete')
            ->where('orderable_id', $session->course()->first()->id)
            ->where('orderable_type', get_class($session->course()->first()))
            ->first();

        if ($has_order != null) {
            //PDF file is stored under project/public/download/info.pdf
            $file = env('APP_URL_2') . '/' . $session['video'];

            $headers = array(
                'Content-Type: application/pdf',
            );

            return Response::download($file, 'filename.pdf', $headers);
            // return response()->json(['status' => true, 'data' =>   env('APP_URL_2'). '/' . $session['video']]);
        }
    }
}
