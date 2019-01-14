<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Avance;
use App\Partida;
use App\Lote;
use DB;

class AvanceController extends Controller
{
    public function store($lote_id, $partida_id){
        $avance = new Avance();
        $avance->lote_id = $lote_id;
        $avance->partida_id = $partida_id;
        $avance->save();
    }
}
