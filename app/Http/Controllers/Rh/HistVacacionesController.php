<?php

namespace App\Http\Controllers\Rh;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\HistVacation;
use App\MedioDia;
use App\Vacation;
use Auth;
use DB;

class HistVacacionesController extends Controller
{
    public function index(Request $request){
        $data = HistVacation::join('users as u', 'u.id', '=', 'hist_vacations.user_id')
            ->join('personal as p', 'p.id', '=', 'u.id')
            ->join('vacations','vacations.id','=','hist_vacations.vacation_id')
            ->select('hist_vacations.*', 'vacations.anio');
            if($request->user_id != '')
                $data = $data->where('hist_vacations.user_id', '=', $request->user_id);
            else
                $data = $data->where('hist_vacations.user_id', '=', Auth::user()->id);
            $data = $data->orderBy('hist_vacations.status', 'asc')
            ->orderBy('hist_vacations.f_ini', 'desc')
            ->paginate(10);
        return $data;
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

            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }
    }
}
