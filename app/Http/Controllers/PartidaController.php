<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Partida;
use App\Lote;

class PartidaController extends Controller
{
    public function store($id, $nombre)
    {
        //if(!$request->ajax())return redirect('/');
        $partida = new Partida();
        $partida->partida = $nombre;
        $partida->lote_id = $id;
        $partida->save();
    }
}
