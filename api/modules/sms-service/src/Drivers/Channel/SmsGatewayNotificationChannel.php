<?php

namespace Tamani\Sms\Drivers\Channel;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tamani\Sms\Enums\SmsMessageStatus;
use Tamani\Sms\Enums\SmsMessageTypes;
use Tamani\Sms\Models\SmsMessage;

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

        $this->writeConfig($config);
        $this->writeToDb($config);
    }

    protected function writeToDb(array $config)
    {
        $message = new SmsMessage();
        $message->message = $config['MESSAGE'];
        $message->type = SmsMessageTypes::OUTGOING;
        $message->status = SmsMessageStatus::PENDING;
        $message->recipient = $config['PHONE_NUMBER'];
        $message->save();
    }

    protected function writeConfig(array $config)
    {
        $configTxt = "";
        foreach ($config as $idx => $conf) {
            $configTxt .= $idx . '=' . $conf . PHP_EOL;
        }

        $filename = strtolower(Str::random(6));

        Storage::put('sms-jobs/' . $filename, $configTxt);
    }
}
