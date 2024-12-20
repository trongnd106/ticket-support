<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CommentEmailNotification extends Notification
{
    use Queueable;

    private $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->line('New comment on your ticket:')
            ->line($this->message)
            ->action('View Ticket', url('/tickets/' . $this->message->ticket_id))
            ->line('Thank you for using our application!');
    }
}
