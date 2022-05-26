<?php

namespace App\Notifications;

use App\User;
use App\Personal;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use NotificationChannels\AwsPinpoint\AwsPinpointChannel;
use NotificationChannels\AwsPinpoint\AwsPinpointSmsMessage;

class PhoneVerificationCreated extends Notification
{

    public $GlobalDatos;

    public function __construct(String $datos)
    {
        $this->GlobalDatos = $datos;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param \App\User $notifiable
     * @return array
     */
    public function via(User $notifiable)
    {
        return [AwsPinpointChannel::class];
    }

    /**
     * Send SMS via AWS Pinpoint.
     *
     * @param \App\User $notifiable
     * @return \NotificationChannels\AwsPinpoint\AwsPinpointSmsMessage
     */
    public function toAwsPinpoint(User $notifiable)
    {
        $message = $this->GlobalDatos;
        $p = Personal::findOrFail($notifiable->id);
        $numero = ''.$p->clv_lada.$p->celular;
        return (new AwsPinpointSmsMessage($message))
            ->setRecipients($numero);
    }
}