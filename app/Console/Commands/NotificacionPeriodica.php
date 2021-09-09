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
        $today = Carbon::today()->format('Y-m-d');
        $notificaciones = Notificacion_aviso::select('id','periodo','created_at','updated_at')->where('periodo','!=',0)->get();

        if(sizeof($notificaciones))
            foreach ($notificaciones as $key => $n) {
                $n->nuevaFecha = new Carbon($n->updated_at);
                $n->nuevaFecha = $n->nuevaFecha->addDays($n->periodo)->format('Y-m-d');
                if($n->nuevaFecha == $today){
                    $notificacion = Notificacion_aviso::findOrFail($n->id);
                    $notificacion->enterado = 0;
                    $notificacion->save();
                }
            }
    }
}
