<?php

namespace App\Notifications;

use App\Mail\TaskChanged;
use App\Contracts\EventModelMailableInterface;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ModelEvent extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(public EventModelMailableInterface $event)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new TaskChanged($this->event->getModel()))->to(address:$notifiable->email);
    }
}
