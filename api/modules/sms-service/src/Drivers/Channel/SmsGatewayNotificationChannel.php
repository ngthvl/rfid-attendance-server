<?php

namespace App\Support\Drivers\Channel;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SmsGatewayNotificationChannel
{
    public function send($notifiable, UsesSmsGateway $notification)
    {
        $message = $notification->toSmsGateway($notifiable);
        $contact = $notification->getPhoneNumber($notifiable);
        $config = [];

        $config['WEBHOOK'] = route('sms-server-wh', ['tag'=>'testtag']);
        $config['COMMAND'] = 'send_sms';
        $config['PHONE_NUMBER'] = $contact;
        $config['MESSAGE'] = $message;

        $configTxt = "";
        foreach ($config as $idx => $conf) {
            $configTxt .= $idx . '=' . $conf . PHP_EOL;
        }

        $filename = strtolower(Str::random(6));

        Storage::put('sms-jobs/' . $filename, $configTxt);
    }
}
