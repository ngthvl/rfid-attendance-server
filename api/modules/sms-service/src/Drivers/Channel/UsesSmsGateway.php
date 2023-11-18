<?php


namespace Tamani\Sms\Drivers\Channel;


interface UsesSmsGateway
{
    public function toSmsGateway($notifiable): string;
    public function getPhoneNumber($notifiable): string;
}
