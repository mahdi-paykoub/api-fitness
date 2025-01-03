<?php


namespace App\Services;

use App\Models\Option;
use Melipayamak\MelipayamakApi;

class SendSms
{
    public function hasPerpision($key)
    {
        return Option::where('key', $key)->first();
    }
    public function sendTicketNotifToAdmin($user_name)
    {
        if ($this->hasPerpision('ADMIN_TICKET_SMS') != null) {
            if ($this->hasPerpision('ADMIN_TICKET_SMS')->value == 1) {
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
        }
    }
    public function sendUserRegisterNotifToAdmin($user_name)
    {
        if ($this->hasPerpision('ADMIN_REGISTER_SMS') != null) {
            if ($this->hasPerpision('ADMIN_REGISTER_SMS')->value == 1) {
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
        }
    }
    public function sendTicketNotifToUser($to, $user_name)
    {
        if ($this->hasPerpision('USER_TICKET_SMS') != null) {
            if ($this->hasPerpision('USER_TICKET_SMS')->value == 1) {
                $username = env('MELIPAYAMAK_USERNAME');
                $password = env('MELIPAYAMAK_PASSWORD');

                $api = new MelipayamakApi($username, $password);
                $sms = $api->sms('soap');
                $from = '50004001093319';

                $response = $sms->sendByBaseNumber($user_name, $to, 112746);
            }
        }
    }
    public function sendRegisterPlanNotifToUser($to, $user_name, $pack_type, $code_peygiri)
    {
        if ($this->hasPerpision('USER_WELCOME_SMS') != null) {
            if ($this->hasPerpision('USER_WELCOME_SMS')->value == 1) {
                $username = env('MELIPAYAMAK_USERNAME');
                $password = env('MELIPAYAMAK_PASSWORD');

                $api = new MelipayamakApi($username, $password);
                $sms = $api->sms('soap');
                $from = '50004001093319';

                $response = $sms->sendByBaseNumber(array($user_name, $pack_type, $code_peygiri), $to, 113233);
            }
        }
    }
    public function sendProgramNotifToUser($to, $user_name)
    {
        if ($this->hasPerpision('USER_PROGRAM_SMS') != null) {
            if ($this->hasPerpision('USER_PROGRAM_SMS')->value == 1) {
                $username = env('MELIPAYAMAK_USERNAME');
                $password = env('MELIPAYAMAK_PASSWORD');

                $api = new MelipayamakApi($username, $password);
                $sms = $api->sms('soap');
                $from = '50004001093319';

                $response = $sms->sendByBaseNumber($user_name, $to, 116576);
            }
        }
    }
    public function sendToVisitUsers($to, $user_name)
    {
        if ($this->hasPerpision('USER_VISIT_SMS') != null) {
            if ($this->hasPerpision('USER_VISIT_SMS')->value == 1) {
                $username = env('MELIPAYAMAK_USERNAME');
                $password = env('MELIPAYAMAK_PASSWORD');

                $api = new MelipayamakApi($username, $password);
                $sms = $api->sms('soap');
                $from = '50004001093319';

                $response = $sms->sendByBaseNumber($user_name, $to, 119380);
            }
        }
    }



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
