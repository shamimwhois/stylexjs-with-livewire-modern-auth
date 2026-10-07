<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MagicLinkLogin extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public readonly string $url,
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
            ->subject(__('auth.magic.mail_subject'))
            ->greeting(__('auth.magic.mail_greeting'))
            ->line(__('auth.magic.mail_line_intro', ['minutes' => $this->ttlMinutes]))
            ->action(__('auth.magic.mail_button'), $this->url)
            ->line(__('auth.magic.mail_line_ignore'));
    }
}
