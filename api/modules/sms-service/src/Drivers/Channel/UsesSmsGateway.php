<?php


namespace App\Support\Drivers\Channel;


interface UsesSmsGateway
{
    public function toSmsGateway($notifiable): string;
    public function getPhoneNumber($notifiable): string;
}
