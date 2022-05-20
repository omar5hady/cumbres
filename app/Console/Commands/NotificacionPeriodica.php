<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\NotificacionesAvisosController;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificationReceived;
use App\Notificacion_aviso;
use Carbon\Carbon;
use App\Pago_renta;
use App\User;

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
        $this->getNotificacionesAviso($today);
        $this->getPagosPorVencer();
        $this->cambiarPagoAVencido();
    }

    private function getNotificacionesAviso($today){
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

    private function getPagosPorVencer(){
        //Se establece la fecha 5 dias posteriores a la actual.
        $fecha = Carbon::today()->addDays(5)->format('Y-m-d');
        $pagos = $this->getPagaresPendientes($fecha,1);

        if(sizeof($pagos))
        {
            $users = User::join('personal', 'users.id', '=', 'personal.id')
                ->select('personal.id','personal.email')->whereIn('users.usuario',
                        ['uriel.al','enrique.mag','shady'])->get();

            foreach($pagos as $ind => $pago){
                $monto = number_format((float)$pago->importe, 2, '.', ',');
                $msj = 'El Pago #'.$pago->num_pago.' por la cantidad de $'.$monto.' a nombre de '.$pago->nombre_arrendatario.' esta próximo a vencer';
                foreach ($users as $index => $user) {
                    $aviso = new NotificacionesAvisosController();
                    $correo = $user->email;
                    Mail::to($correo)->send(new NotificationReceived($msj));
                    $aviso->store($user->id,$msj);
                }
            }
        }

    }

    private function getPagaresPendientes($fecha,$status){
        return $pagos = Pago_renta::join('rentas','pagos_rentas.renta_id','=','rentas.id')
                    ->join('lotes','rentas.lote_id','=','lotes.id')
                    ->join('etapas','lotes.etapa_id','=','etapas.id')
                    ->join('fraccionamientos','lotes.fraccionamiento_id','=','fraccionamientos.id')
                    ->select('pagos_rentas.*','rentas.nombre_arrendatario',
                        'lotes.calle','lotes.numero','lotes.interior', 'lotes.manzana',
                        'etapas.num_etapa as etapa', 'fraccionamientos.nombre as proyecto'
                    )
                    ->where('pagos_rentas.fecha','=',$fecha)
                    ->where('pagos_rentas.status','=',$status)
                    ->where('rentas.status','=',2)
                    ->orderBy('id','desc')
                    ->get();
    }

    private function cambiarPagoAVencido(){
        $fecha = Carbon::today()->format('Y-m-d');
        $pagos = $this->getPagaresPendientes($fecha,1);

        if(sizeof($pagos))
            foreach($pagos as $index => $pago){
                $p = Pago_renta::findOrFail($pago->id);
                $p->status = 0;
                $p->save();
            }
    }
}
