<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;

class NewDeviceLogin extends Notification
{
    /**
     * Create a new notification instance.
     */
    public function __construct(
        public readonly ?string $ip,
        public readonly string $userAgent,
        public readonly Carbon $time,
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('auth.device.mail_subject'))
            ->greeting(__('auth.device.mail_greeting'))
            ->line(__('auth.device.mail_line_intro'))
            ->line(__('auth.device.mail_line_time', ['time' => $this->time->format('Y-m-d H:i:s T')]))
            ->line(__('auth.device.mail_line_ip', ['ip' => $this->ip ?? __('auth.device.unknown_ip')]))
            ->line(__('auth.device.mail_line_browser', ['browser' => $this->userAgent]))
            ->line(__('auth.device.mail_line_help'));
    }
}
