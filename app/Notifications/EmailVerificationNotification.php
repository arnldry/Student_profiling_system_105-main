<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailVerificationNotification extends Notification
{
    use Queueable;

    protected $verificationToken;

    /**
     * Create a new notification instance.
     */
    public function __construct($verificationToken)
    {
        $this->verificationToken = $verificationToken;
    }

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
        $verificationUrl = route('email.verify', ['token' => $this->verificationToken]);
        
        return (new MailMessage)
            ->subject('Verify Your Email Address - Student Profiling System')
            ->greeting('Hello!')
            ->line('Thank you for registering with the Student Profiling System.')
            ->line('Please click the button below to verify your email address and create your password.')
            ->action('Verify Email & Create Password', $verificationUrl)
            ->line('This verification link will expire in 5 minutes.')
            ->line('If you did not create an account, no further action is required.')
            ->salutation('Best regards, Student Profiling System Team');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
