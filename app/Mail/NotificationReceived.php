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
     * Crear una nueva instancia
     *
     * @return void
     */
    public $mensaje;
    public $ruta;
    public function __construct(String $mensaje1, String $ruta='')
    {
        //Se asigna el mensaje y ruta 
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
        //En caso de no ser indicado una ruta, se manda llamar la notificación general
        if($this->ruta == '')
            return $this->view('mails.mail_notification',['mensaje' => $this->mensaje]);
        else
            //Caso contrario se manda llamar la notificación para integración de cobros.
            return $this->view('mails.mail_int_cobros',[
                            'mensaje' => $this->mensaje,
                            'ruta' => $this->ruta,
                                ]);
    }
}
