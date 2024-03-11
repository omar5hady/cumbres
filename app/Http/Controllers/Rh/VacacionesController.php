<?php

namespace App\Http\Controllers\Rh;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Vacation;
use Auth;
use Carbon\Carbon;

class VacacionesController extends Controller
{
    public function index(Request $request){
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

        return $data;
    }
}
