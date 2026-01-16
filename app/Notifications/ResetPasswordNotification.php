<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification
{
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Reset Your Password - My App')
            ->greeting('Hello, ' . $notifiable->name)
            ->line('You requested a password reset. Click the button below to reset your password:')
            ->action('Reset Password', url(route('password.reset', $this->token, false)))
            ->line('If you did not request this, you can ignore this email.');
    }
}