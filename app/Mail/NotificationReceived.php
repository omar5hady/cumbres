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
    public $ruta;
    public function __construct(String $mensaje1, String $ruta='')
    {
        $this->mensaje = $mensaje1;
        $this->ruta = $ruta;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->ruta == '')
            return $this->view('mails.mail_notification',['mensaje' => $this->mensaje]);
        else
            return $this->view('mails.mail_int_cobros',[
                            'mensaje' => $this->mensaje,
                            'ruta' => $this->ruta,
                                ]);
    }
}
