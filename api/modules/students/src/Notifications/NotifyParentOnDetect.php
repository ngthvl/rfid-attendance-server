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

    protected RfidOutput $detection;

    /**
     * Create a new notification instance.
     */
    public function __construct(RfidOutput $detection)
    {
        $this->detection = $detection;
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
        return "Hi there!, your student: " . $notifiable->first_name . ' ' . $notifiable->last_name . ' was detected at school at: ' . $this->detection->detection_dt;
    }

    public function getPhoneNumber($notifiable): string
    {
        return $notifiable->contact_number;
    }

    public function uniqueID(): string
    {
        return $this->detection->detected_uid;
    }
}
