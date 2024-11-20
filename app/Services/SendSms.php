<?php


namespace App\Services;

use App\Models\Option;
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
    
    public function sendTicketNotifToUser($to, $user_name)
    {
        $username = env('MELIPAYAMAK_USERNAME');
        $password = env('MELIPAYAMAK_PASSWORD');

        $api = new MelipayamakApi($username, $password);
        $sms = $api->sms('soap');
        $from = '50004001093319';

        $response = $sms->sendByBaseNumber($user_name, $to, 112746);
    }

    public function sendRegisterPlanNotifToUser($to, $user_name, $pack_type, $code_peygiri)
    {
        $username = env('MELIPAYAMAK_USERNAME');
        $password = env('MELIPAYAMAK_PASSWORD');

        $api = new MelipayamakApi($username, $password);
        $sms = $api->sms('soap');
        $from = '50004001093319';

        $response = $sms->sendByBaseNumber(array($user_name, $pack_type, $code_peygiri), $to, 113233);
    }

    public function sendProgramNotifToUser($to, $user_name)
    {
        $username = env('MELIPAYAMAK_USERNAME');
        $password = env('MELIPAYAMAK_PASSWORD');

        $api = new MelipayamakApi($username, $password);
        $sms = $api->sms('soap');
        $from = '50004001093319';

        $response = $sms->sendByBaseNumber($user_name, $to, 116576);
    }

    public function sendTicketNotifToAdmin($user_name)
    {
        $adminPhone = Option::where('key', 'ADMIN_TICKET_PHONE')->first();
        if ($adminPhone != null) {
            $username = env('MELIPAYAMAK_USERNAME');
            $password = env('MELIPAYAMAK_PASSWORD');

            $api = new MelipayamakApi($username, $password);
            $sms = $api->sms('soap');
            $from = '50004001093319';

            $response = $sms->sendByBaseNumber($user_name, $adminPhone->value, 116566);
        }
    }

    public function sendUserRegisterNotifToAdmin($user_name)
    {
        $adminPhone = Option::where('key', 'ADMIN_TICKET_PHONE')->first();
        if ($adminPhone != null) {
            $username = env('MELIPAYAMAK_USERNAME');
            $password = env('MELIPAYAMAK_PASSWORD');

            $api = new MelipayamakApi($username, $password);
            $sms = $api->sms('soap');
            $from = '50004001093319';

            $response = $sms->sendByBaseNumber($user_name, $adminPhone->value, 116567);
        }
    }

    public function sendToVisitUsers($to, $user_name)
    {
        $adminPhone = Option::where('key', 'ADMIN_TICKET_PHONE')->first();
        if ($adminPhone != null) {
            $username = env('MELIPAYAMAK_USERNAME');
            $password = env('MELIPAYAMAK_PASSWORD');

            $api = new MelipayamakApi($username, $password);
            $sms = $api->sms('soap');
            $from = '50004001093319';

            $response = $sms->sendByBaseNumber($user_name, $to, 119380);
        }
    }
}
