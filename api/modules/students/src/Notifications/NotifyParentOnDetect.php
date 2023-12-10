<?php

namespace Tamani\Students\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Tamani\RfidTerminal\Models\RfidOutput;
use Tamani\Sms\Drivers\Channel\SmsGatewayNotificationChannel;
use Tamani\Sms\Drivers\Channel\UsesSmsGateway;
use function url;

class NotifyParentOnDetect extends Notification implements UsesSmsGateway
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(RfidOutput $detection)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [SmsGatewayNotificationChannel::class];
    }

    public function toSmsGateway($notifiable): string
    {
        return "hello";
    }

    public function getPhoneNumber($notifiable): string
    {
        return "639457201016";
    }
}
