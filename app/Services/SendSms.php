<?php


namespace App\Services;

use Melipayamak\MelipayamakApi;

class SendSms
{
    public function sendCode($to, $text)
    {

        $username = env('MELIPAYAMAK_USERNAME');
        $password = env('MELIPAYAMAK_PASSWORD');
        $api = new MelipayamakApi($username, $password);
        $sms = $api->sms('soap');
        $from = '50004001093319';

        $response = $sms->sendByBaseNumber($text, $to, 112743);
    }
}
