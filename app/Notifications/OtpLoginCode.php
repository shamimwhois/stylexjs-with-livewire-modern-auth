<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OtpLoginCode extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public readonly string $code,
        public readonly int $ttlMinutes,
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
            ->subject(__('auth.otp.mail_subject'))
            ->greeting(__('auth.otp.mail_greeting'))
            ->line(__('auth.otp.mail_line_code'))
            ->line("**{$this->code}**")
            ->line(__('auth.otp.mail_line_expiry', ['minutes' => $this->ttlMinutes]))
            ->line(__('auth.otp.mail_line_ignore'));
    }
}
