<?php

namespace App\Http\Controllers\Rh;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Rh\ObsVacationController;
use App\HistVacation;
use App\MedioDia;
use App\Vacation;
use Auth;
use Carbon\Carbon;
use DB;

class HistVacacionesController extends Controller
{
    public function index(Request $request){
        $data = HistVacation::join('users as u', 'u.id', '=', 'hist_vacations.user_id')
            ->join('personal as p', 'p.id', '=', 'u.id')
            ->join('vacations','vacations.id','=','hist_vacations.vacation_id')
            ->select('hist_vacations.*', 'vacations.anio', 'p.nombre', 'p.apellidos');
            if($request->admin == ''){
                if($request->user_id != '')
                    $data = $data->where('hist_vacations.user_id', '=', $request->user_id);
                else
                    $data = $data->where('hist_vacations.user_id', '=', Auth::user()->id);
            }

            else{
                $usuario = Auth::user()->usuario;
                $gerente = $this->checkGerente($usuario);
                $admin = 0;
                if( $usuario == 'marce.gaytan' || $usuario == 'shady')
                    $admin = 1;

                if($admin == 0){
                    if($gerente > 0)
                        $data = $data->where('p.departamento_id','=',$gerente);
                    else
                        $data = $data->where('hist_vacations.jefe_id','=', Auth::user()->id);
                }

                if($request->nombre != '')
                    $data = $data->where(DB::raw("CONCAT(p.nombre,' ',p.apellidos)"), 'like', '%'. $request->nombre . '%');
            }
            if($request->fechaIni != '' && $request->fechaFin){
                $data = $data->where('hist_vacations.f_ini','>=',$request->fechaIni)
                            ->where('hist_vacations.f_fin','<=',$request->fechaFin);
            }
            if($request->status != '')
                $data = $data->where('hist_vacations.status', '=', $request->status);

            $data = $data->orderBy('hist_vacations.id', 'desc')
            ->orderBy('hist_vacations.status', 'asc')

            ->paginate(10);
        return $data;
    }

    private function checkGerente($usuario){

        $gerente = 0;
        if( $usuario == 'eli_hdz' ) //Comercializacion 9
            $gerente = 9;
        if($usuario == 'sajid.m' ) //Postventa 4
            $gerente = 4;
        if($usuario == 'bd_raul' ) //Proyectos 3
            $gerente = 3;
        if($usuario == 'lucy.hdz' )//Presupuestos 5
            $gerente = 5;
        if($usuario == 'cp.martin' )//Administracion 7
            $gerente = 7;
        if($usuario == 'ing_david' )//Direccion 1
            $gerente = 1;
        if($usuario == 'meza.marco60' )//Contabilidad 6
            $gerente = 6;
        if($usuario == 'guadalupe.ff' )// Obra 2
            $gerente = 2;

        return $gerente;
    }

    private function insertDays($dias, $id){
        foreach($dias as $d){
            $dia = new MedioDia();
            $dia->hist_id = $id;
            $dia->fecha = $d['fecha'];
            $dia->medio_dia = $d['medio_dia'];
            $dia->horario = $d['horario'];
            $dia->save();
        }
    }

    private function actualizarVacations($request){
        $vacation = Vacation::findOrFail($request->vacation_id);
        $vacation->saldo = $request->saldo;
        $vacation->dias_tomados += $request->dias_tomados;
        $vacation->save();
    }

    public function getDetalleDias(Request $request){
        $dias = MedioDia::where('hist_id','=',$request->id)->get();
        return $dias;
    }

    public function store(Request $request){
        $datos_dias = $request->datos_dias;

        try{
            DB::beginTransaction();

            $hist = new HistVacation();
            $hist->user_id = Auth::user()->id;
            $hist->f_ini = $request->f_ini;
            $hist->f_fin = $request->f_fin;
            $hist->nota = $request->nota;
            $hist->vacation_id = $request->vacation_id;
            $hist->dias_tomados = $request->dias_tomados;
            $hist->dias_elegidos = $request->dias_elegidos;
            $hist->dias_disponibles = $request->dias_disponibles;
            $hist->dias_festivos = $request->dias_festivos;
            $hist->saldo = $request->saldo;
            $hist->jefe_id = $request->jefe_id;
            $hist->save();

            $id = $hist->id;

            $this->insertDays($datos_dias, $id);
            $this->actualizarVacations($request);

            $obsController = new ObsVacationController();
            $obsController->saveObservation($id, 'Solicitud creada');

            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }

    public function revisarRH(Request $request){
        $id = $request->id;
        $solicitud = HistVacation::findOrFail($id);
        $solicitud->f_rh = Carbon::now();
        $solicitud->save();

        $obsController = new ObsVacationController();
        $obsController->saveObservation($id, 'Solicitud revisada');
    }

    public function autorizarSolicitud(Request $request){
        $id = $request->id;
        $solicitud = HistVacation::findOrFail($id);
        $solicitud->status = 'aprobado';
        $solicitud->f_jefe = Carbon::now();
        $solicitud->save();

        $obsController = new ObsVacationController();
        $obsController->saveObservation($id, 'Solicitud aprobada');
    }

    public function rechazarSolicitud(Request $request){
        $id = $request->id;
        $solicitud = HistVacation::findOrFail($id);
        $solicitud->status = 'rechazado';

        $vacation = Vacation::findOrFail($solicitud->vacation_id);
        $vacation->saldo = $solicitud->dias_disponibles;
        $vacation->dias_tomados -= $solicitud->dias_tomados;
        $vacation->save();
        $solicitud->save();

        $obsController = new ObsVacationController();
        $obsController->saveObservation($id, 'Solicitud rechazada');
    }
}
