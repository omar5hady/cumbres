<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Notificacion_aviso;
use Carbon\Carbon;

class NotificacionPeriodica extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // Comando de activación
    protected $signature = 'updatePeriod:notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Habilita las notificaciones que deben mostrarse periodicamente al llegar a la fecha programada';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //Se establece la fecha actual.
        $today = Carbon::today()->format('Y-m-d');
        //Arreglo con las notificaciones que sean de aviso periodico
        $notificaciones = Notificacion_aviso::select('id','periodo','created_at','updated_at','finPeriodo')->where('periodo','!=',0)->get();

        if(sizeof($notificaciones))
            //Se recorre el arreglo
            foreach ($notificaciones as $key => $n) {
                //Se estable la fecha final del periodo a enviar por cada uno de los registros
                $fin = new Carbon($n->finPeriodo);
                //Se verifica si la fecha de finalización periodica aun no ha ocurrido.
                if(!$fin->isPast()){
                    //Aqui se calcula la ultima fecha en la que se envio la notificación
                    $n->nuevaFecha = new Carbon($n->updated_at);
                    $n->nuevaFecha = $n->nuevaFecha->addDays($n->periodo)->format('Y-m-d');
                    //Si la ultima fecha de notificacion + 1 dia es la misma que hoy se envia de nuevo la notificación al usuario.
                    if($n->nuevaFecha == $today){
                        $notificacion = Notificacion_aviso::findOrFail($n->id);
                        $notificacion->enterado = 0;
                        $notificacion->save();
                    }
                }
            }
    }
}
