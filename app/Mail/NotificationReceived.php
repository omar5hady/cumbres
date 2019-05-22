<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotificationReceived extends Mailable
{
    use Queueable, SerializesModels;
    

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $mensaje;
    public function __construct(String $mensaje1)
    {
        $this->mensaje = $mensaje1;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.mail_notification',['mensaje' => $this->mensaje]);
    }
}
