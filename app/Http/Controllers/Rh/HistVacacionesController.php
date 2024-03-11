<?php

namespace App\Http\Controllers\Rh;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\HistVacation;
use Auth;

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
            ->orderBy('vacations.anio', 'desc')
            ->paginate(10);
        return $data;
    }
}
