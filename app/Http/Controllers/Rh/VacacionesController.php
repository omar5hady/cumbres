<?php

namespace App\Http\Controllers\Rh;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Vacation;
use App\HistVacation;
use Auth;
use App\User;
use App\Personal;
use Carbon\Carbon;

class VacacionesController extends Controller
{
    public function index(Request $request){
        $hist = HistVacation::where('status','=','pendiente');
        if($request->user_id != '')
        $hist = $hist->where('hist_vacations.user_id', '=', $request->user_id);
        else
            $hist = $hist->where('hist_vacations.user_id', '=', Auth::user()->id);
        $hist = $hist->count();

        $data = Vacation::join('users as u', 'u.id', '=', 'vacations.user_id')
            ->join('personal as p', 'p.id', '=', 'u.id')
            ->select('vacations.*', 'u.foto_user', 'p.nombre', 'p.apellidos');
            if($request->user_id != '')
                $data = $data->where('vacations.user_id', '=', $request->user_id);
            else
                $data = $data->where('vacations.user_id', '=', Auth::user()->id);
            $data = $data->orderBy('vacations.status', 'asc')
            ->orderBy('vacations.anio', 'desc')
            ->take(5)
            ->get();

        return [
            'datos' => $data,
            'countPendiente' =>$hist
        ];
    }

    public function getJefes(Request $request){
        $usuario = Personal::select('departamento_id')->where('id','=', Auth::user()->id)->get();

        if(sizeof($usuario)){
            $jefes = User::join('personal','personal.id','=','users.id')
            ->select('users.id','personal.nombre', 'personal.apellidos')
                ->where('users.vacaciones','=',1)
                ->where('personal.departamento_id','=',$usuario[0]->departamento_id)
                ->orWhereIn('users.usuario',['ing_david', 'alejandro.pe'])
                ->orderBy('personal.nombre', 'asc')
                ->get();

            return $jefes;
        }
    }
}
